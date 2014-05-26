<?php /* Smarty version Smarty-3.1.14, created on 2014-05-26 19:16:55
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\login\login.html" */ ?>
<?php /*%%SmartyHeaderCode:3842538322a7a18020-79770024%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '99c61efffd89a96176166d4140f78775c6ce1c17' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\login\\login.html',
      1 => 1401102773,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3842538322a7a18020-79770024',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_538322a7aa70e9_19517057',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_538322a7aa70e9_19517057')) {function content_538322a7aa70e9_19517057($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>首页</title>
<meta name="description" content="" />
<meta name="keywords" content="">
<link rel="stylesheet" type="text/css" href="<?php echo static_url('/css/login/style.css');?>
" />
<!-- 加载头部公共变量定义开始 -->
<!--#include virtual="/include/var.htm"-->
<?php echo $_smarty_tpl->getSubTemplate ("www/include/var.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- 加载头部公共变量定义结束 -->
</head>
<body>
<!-- 头部开始 -->
<!--#include virtual="/include/header.htm"-->
<?php echo $_smarty_tpl->getSubTemplate ("www/include/header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- 头部结束 -->
<!-- 主要内容开始 -->
<div class="wrap layout login" id="nahaoModule" module="login">
	<div class="loginWrap cf">
		<div class="loginBox fr">
			<form action class="loginForm" mothed="post" onsubmit="return false">
				<p id="msgInfo" class="msgInfo"></p>
				<ul>
					<li>
						<input type="text" value="" class="userName yh" placeholder="手机号/邮箱/梯子网帐号">
						<div class="ValidInfo undis">
							<span class="Validform_checktip"></span>
							<span class="dec">
								<s class="dec1">&#9670;</s>
								<s class="dec2">&#9670;</s>
							</span>
						</div>
					</li>
					<li>
						<input type="password" value="" class="pwd yh" placeholder="密码">
						<div class="ValidInfo undis">
							<span class="Validform_checktip"></span>
							<span class="dec">
								<s class="dec1">&#9670;</s>
								<s class="dec2">&#9670;</s>
							</span>
						</div>
					</li>
					<li class="cf autoLi">
						<input type="radio" id="autoLogin" class="fl autoLogin">
						<label for="autoLogin" class="fl">自动登录</label>
						<a href="javascript:void(0)" class="fr">忘记密码？</a>
					</li>
					<li>
						<input type="submit" class="redBtn yh btn redBtn submit" value="登录">
					</li>
					<li>
						<a href="javascript:void(0)" class=" btn blueBtn">忘记密码？</a>
					</li>
				</ul>
			</form>
		</div>
	</div>
</div>
<!-- 主要内容结束 -->
<!-- 底部开始 -->
<!--#include virtual="/include/footer.htm"-->
<?php echo $_smarty_tpl->getSubTemplate ("www/include/footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- 底部结束 -->
<!-- js引入开始 -->
<!--#include virtual="/include/js.htm"-->
<?php echo $_smarty_tpl->getSubTemplate ("www/include/js.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!--js引入结束-->
</body>
</html><?php }} ?>