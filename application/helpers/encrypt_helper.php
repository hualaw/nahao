<?php
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