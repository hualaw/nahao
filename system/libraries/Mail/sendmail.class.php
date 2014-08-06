<?php

require('phpmailer.class.php');
//利用phpmailer发送
class sendmail{
	//发送邮件
	/*
	*$subject:主题
	*$body:主题
	*$address_arr:收件人列表
	*$is_file:true(是) false(否)是否为附件,默认发送内容
	*/
	public function tosend($subject='',$body='',$address_arr=array(),$is_file=false){
		
		$mail=new PHPMailer();
		$mail->IsSMTP(); // 使用SMTP方式发送
		$mail->SMTPAuth = true; // 启用SMTP验证功能
		$mail->CharSet='UTF-8';// 设置邮件的字符编码

		$mail->Host = 'smtp.qq.com'; // 企业邮箱域名
		$mail->Port = 25; //SMTP端口

		
		$mail->Username = 'sundongdong@tizi.com'; //邮箱用户名
		$mail->Password = 'test.123'; // 邮箱密码
		$mail->From = 'sundongdong@tizi.com'; //邮件发送者email地址
		$mail->FromName = '梯子数据统计';

		$mail->IsHTML(true); //是否使用HTML格式
		$mail->Subject = $subject; //邮件标题

		
		if($is_file){//为附件
			if($body){
				$mail->Body='ip登录数据附件';
				foreach($body as $file){
					$mail->AddAttachment($file);
				}
			}
		}else{
			$mail->Body = $body; //邮件内容
		}

		foreach($address_arr as $v){
			$mail->AddAddress($v,'');
		}

		$flag=$mail->Send();
		return $flag;
	}

	//发送邮件给tizi用户
	/*
	*$subject:主题
	*$body:主题
	*$email:收件人
	*$name:收件人姓名
	*/
	public function tosendtizi($subject='',$body='',$email='',$name=''){
		$flag=false;
		$mail=new PHPMailer();
		$mail->IsSMTP(); // 使用SMTP方式发送
		$mail->SMTPAuth = true; // 启用SMTP验证功能
		$mail->CharSet='UTF-8';// 设置邮件的字符编码

		$mail->Host = 'smtp.qq.com';//企业邮箱域名
		$mail->Port = 25; //SMTP端口
		
		$mail->Username = 'ghy@tizi.com'; //邮箱用户名
		$mail->Password = 'fabuhui1124'; // 邮箱密码
		$mail->From = 'ghy@tizi.com'; //邮件发送者email地址
		$mail->FromName = '龚海燕';

		$mail->IsHTML(true); //是否使用HTML格式
		$mail->Subject=$subject; //邮件标题
		$mail->Body=$body; //邮件内容

		if($email){
			$mail->AddAddress($email,$name);
			$flag=$mail->Send();
			//echo $mail->ErrorInfo.'----';
		}
		
		return $flag;
	}

	//搜狐代发邮件
	public function tosendsohu($to_mail_addr, $subject, $mail_content, $debug=false){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, 'https://sendcloud.sohu.com/webapi/mail.send.json');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //allow maximum 5 seconds to execute
		//不同于登录SendCloud站点的帐号，您需要登录后台创建发信子帐号，使用子帐号和密码才可以进行邮件的发送。
		curl_setopt($ch, CURLOPT_POSTFIELDS,
				array('api_user' => 'postmaster@tizi-com.sendcloud.org',
					'api_key' => 'v33EreaE',
					'from' => 'ghy@daily.tizi.com',
					'fromname' => '龚海燕',
					'to' => $to_mail_addr,
					'subject' => $subject,
					'html' => $mail_content,
					));        

		$result = curl_exec($ch);

		//for test
		if($debug) var_dump($result);

		$http_errno = curl_errno($ch);
		$http_error = 'Success!';
		$http_code = 200;
		$ret = 0; //2 stand for error

		if($http_errno === 0 )
		{
			$result_arr = json_decode($result, TRUE);
			if(isset($result_arr['message']) && $result_arr['message'] == 'success')
			{
				$ret = 1; //success!
			}
			else
			{
				$ret = 2; //http success, but logic error
				$http_error .= "\t".print_r($result_arr, 1);
			}
		}
		else {
			$ret = 3; //http error
			$http_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		}

		curl_close($ch);

		return array(
			'ret' => $ret,
			'http_errno' => $http_errno,
			'http_error' => $http_error,	
			'http_code' => $http_code,
		);
	}




}




?>