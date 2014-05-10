<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	</head>
  <body>
	<?php echo form_open('student_register/check_student'); ?>
		真实姓名:<input type="text" name="realname" /><br />
		年龄:<input type="text" name="age" /><br />
		性别:<input type="text" name="gender" /><br />
		年级:<input type="text" name="grade" /><br />
		感兴趣老师:<input type="text" name="favorite_teacher_type" /><br />
		感兴趣科目:<input type="text" name="favorite_course_type" /><br />
		所在学校:<input type="text" name="favorite_course_type" /><br />
		所在省:<input type="text" name="favorite_course_type" /><br />
		所在市:<input type="text" name="favorite_course_type" /><br />
		所在区:<input type="text" name="favorite_course_type" /><br />
		<input type="hidden" value="<?php echo $stu_id; ?>" name="student_id" />
		<input type="submit" value="提交" />
	</form>
  </body>
</html>