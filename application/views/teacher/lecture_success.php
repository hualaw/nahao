<!DOCTYPE html>
<html lang="en">
	<head>
		<title>那好教师端-试讲申请成功</title>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="那好教师端" />
		<meta name="keywords" content="那好教师端" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo static_url("/admin/css/bootstrap.css"); ?>" rel="stylesheet">
		<link href="<?php echo static_url("/admin/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet">
<style>
body{
font-family: "宋体","Helvetica Neue",Helvetica,Arial,sans-serif;
}
.navbar-brand{
display: block;
float: left;
font-size: 30px;
font-weight: 700;
color: #333;
text-shadow: 0 1px 0 #fff;
transition: all .3s linear;
-webkit-transition: all .3s linear;
-moz-transition: all .3s linear;
}
.navbar-brand:hover{
color: #fff;
}
.body{
padding:0px 15px;
}
.class-notice{
padding: 5px 0px 0px 30px;
}
.class-form{
padding: 5px 0px 15px 30px;
}
</style>
	</head>
	<body>
		<?=$nav?>
		<!--nav end-->
		<!--body-->
		
			<!--left-nav-start-->
	<div class="body">
		<?=$siteBar?>
		<?=$pos?>
	    <?=$success?>
    </div>
			<!--left-end-->
			<!--right-->
	</body>
	<script type="text/javascript" src="<?php echo STATIC_ADMIN_JS_JQUERY_MIN;?>"></script>
	<script type="text/javascript" src="<?php echo static_url(STATIC_ADMIN_JS_BOOTSTRAP_MIN); ?>"></script>
</html>