<?php
	/**
	 * @brief 使用HMAC-SHA1算法生成oauth_signature签名值
	 *
	 * @param $key 密钥
	 * @param $str 源串
	 *
	 * @return 签名值
	 */
	function get_signature($str, $key){
		$signature = "";
		if (function_exists('hash_hmac')){
			$signature = base64_encode(hash_hmac("sha1", $str, $key, true));
		}else{
			$blocksize = 64;
			$hashfunc = 'sha1';
			if (strlen($key) > $blocksize){
				$key = pack('H*', $hashfunc($key));
			}
			$key = str_pad($key,$blocksize,chr(0x00));
			$ipad = str_repeat(chr(0x36),$blocksize);
			$opad = str_repeat(chr(0x5c),$blocksize);
			$hmac = pack('H*',$hashfunc(
			($key^$opad).pack(
			'H*',$hashfunc(
			($key^$ipad).$str
			)
			)
			)
			);
			$signature = base64_encode($hmac);
		}
		return $signature;
	}




	/**
	 * 对用户的密码进行加密
	 * @param $password
	 * @param $encrypt //传入加密串，在修改密码时做认证
	 * @return array/password
	 */
	function tea_create_password($password, $encrypt='') {
	    $pwd = array();
	    $pwd['encrypt'] =  $encrypt ? $encrypt : tea_create_randomstr();
	    $pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
	    return $encrypt ? $pwd['password'] : $pwd;
	}

	/**
	 * 生成随机字符串
	 * @param string $lenth 长度
	 * @return string 字符串
	 */
	function tea_create_randomstr($lenth = 6) {
	    return tea_random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
	}

	/**
	 * 产生随机字符串
	 *
	 * @param    int        $length  输出长度
	 * @param    string     $chars   可选的 ，默认为 0123456789
	 * @return   string     字符串
	 */
	function tea_random($length, $chars = '0123456789') {
	    $hash = '';
	    $max = strlen($chars) - 1;
	    for($i = 0; $i < $length; $i++) {
	        $hash .= $chars[mt_rand(0, $max)];
	    }
	    return $hash;
	}

	/**
	 * 校验密码
	 */
	function tea_check_password($password, $encrypt, $sys_password)
	{
	    return tea_create_password($password, $encrypt) === $sys_password;
	}




	/**
 * @param $string
 * @param string $operation
 * @param int $expiry
 * @param string $key
 * @return string
 */
function authcode($string, $operation = 'DECODE', $expiry = 0, $key = '')
{
    $ckey_length = 4;
    $key = md5($key ? $key : config_item('encryption_key'));
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}

	/**
	 * @param $password
	 * @param $salt
	 * @return string
	 */
	function create_password($password, $salt)
	{
	    return sha1($salt . sha1($password));
	}

	/**
	 * @param $password
	 * @param $salt
	 * @param $sys_password
	 * @return bool
	 */
	function check_password($password, $salt, $sys_password)
	{   
	    return create_password($password, $salt) === $sys_password;
	}

	/**

	openssl genrsa -des3 -out 91waijiao.com.key 1024

	openssl req -new -key 91waijiao.com.key -out 91waijiao.com.csr

	openssl rsa -in 91waijiao.com.key -out 91waijiao.com.key

	openssl asn1parse -out temp.ans -i -inform PEM < 91waijiao.com.key

	**/
	/**
	 * RSA公钥加密
	 *
	 * @param string 明文
	 * @param string 证书文件（.crt）
	 * @return string 密文（base64编码）
	 */
	function publickey_encoding($sourcestr, $fileName)
	{
	    if(is_readable($fileName))
	    { 
	        $key_content = file_get_contents($fileName);
	        $pubkeyid    = openssl_get_publickey($key_content);
	    
	        if (openssl_public_encrypt($sourcestr, $crypttext, $pubkeyid))
	        {
	            return base64_encode("".$crypttext);
	        }
	    }
	    return false;
	}
	/**
	 * RSA私钥解密
	 *
	 * @param string 密文（二进制格式且base64编码）
	 * @param string 密钥文件（.pem /.key）
	 * @param string 密文是否来源于JS的RSA加密
	 * @return string 明文
	 */
	function privatekey_decoding($crypttext, $fileName, $fromjs = FALSE)
	{
	    if(is_readable($fileName))
	    {   
	        $key_content = file_get_contents($fileName);
	        $prikeyid    = openssl_get_privatekey($key_content);
	        $crypttext   = base64_decode($crypttext);
	        $padding = $fromjs ? OPENSSL_NO_PADDING : OPENSSL_PKCS1_PADDING;
	        if (openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, $padding))
	        {
	            return $fromjs ? rtrim(strrev($sourcestr)) : "".$sourcestr;
	        }
	    }
	    return ;
	}