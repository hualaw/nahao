<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	</head>
  <body>
	<?php echo form_open('teacher_register/check_teacher'); ?>
		昵称<input type="text" name="nickname" /><br />
		密码<input type="password" name="password" /><br />
		再次输入密码<input type="password" name="againpassword" /><br />
		邮箱<input type="text" name="email" /><br />
		验证码<input type="text" name="Verification" />
		<?php echo $captcha;?>
		<input type="submit" value="提交" />
	</form>
  </body>
</html>