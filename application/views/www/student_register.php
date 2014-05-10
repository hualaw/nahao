<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	</head>
  <body>
	<?php echo form_open('student_register/check_student'); ?>
		昵称<input type="text" name="nickname" /><br />
		密码<input type="password" name="password" /><br />
		再次输入密码<input type="password" name="againpassword" /><br />
		手机<input type="text" name="phone" /><br />
		验证码<input type="text" name="Verification" />
		<?php echo $captcha;?>
		<input type="submit" value="提交" />
	</form>
	<a href="student_register/student_info?stuid=<?php echo $this->session->userdata('code'); ?>">完善学生信息</a>
  </body>
</html>