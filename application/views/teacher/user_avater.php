<!DOCTYPE html>
<html lang="en">
	<head>
		<title>那好教师端-个人资料【修改头像】</title>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="那好教师端" />
		<meta name="keywords" content="那好教师端" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo static_url("/admin/css/bootstrap.css"); ?>" rel="stylesheet">
		<link href="<?php echo static_url("/admin/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet">
		<link href="<?php echo static_url("/teacher/css/index.css"); ?>" rel="stylesheet">
	</head>
	<body>
		<?=$nav?>
		<div class="body">
			<?=$siteBar?>
			<?=$pos?>
			<?=$user_nav?>
		    <?=$avater?>
	  	</div>
	</body>
	<script type="text/javascript" src="<?php echo STATIC_ADMIN_JS_JQUERY_MIN;?>"></script>
	<script type="text/javascript" src="<?php echo static_url(STATIC_ADMIN_JS_BOOTSTRAP_MIN); ?>"></script>
</html>