<?php /* Smarty version Smarty-3.1.14, created on 2014-05-26 19:15:36
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\login\reg.html" */ ?>
<?php /*%%SmartyHeaderCode:26175383225867c8b7-29275801%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '70f96dd557a4809f49226818e868c530198e6bea' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\login\\reg.html',
      1 => 1401102772,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '26175383225867c8b7-29275801',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_538322587062c3_42061582',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_538322587062c3_42061582')) {function content_538322587062c3_42061582($_smarty_tpl) {?><!doctype html>
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
	<div class="regWrap cf">
		<div class="regBox fl">
			<h2>快速注册<span>一分钟改变你的学习方式</span></h2>
			<div class="regTab">
				<div class="tabBox cf">
					<a href="javascript:void(0)"  class="fl ">手机注册</a>
					<a href="javascript:void(0)"  class="fl active">邮箱注册</a>
				</div>
				<div class="contentBox">
					<div class="box phoneBox undis">
						<ul>
							<li>
								<p>手机号</p>
								<input type="text" class="phoneNum regInput yh" placeholder="使用手机注册，第一时间获知名师公开课">
							</li>
							<li>
								<p>密码</p>
								<input type="password" class="pwd regInput yh" placeholder="6-20 位字符">
							</li>
							<li class="cf">
								<a href="javascript:void(0)" class="  btn blueBtn pnoneCode fl ">免费获取验证码</a>
								<span class="bth pnoneCode undis" >60秒后获取验证码</span>
								<input type="password" class="codeInput regInput yh fr" placeholder="请输入手机验证码">
							</li>
							<li class="agreementLi">
								<input type="radio" id="agreement">
								<label for="agreement">我已阅读并同意</label>
								<a href="javascript:void(0)">那好网服务使用协议</a>
							</li>
							<li>
								<input type="submit" class="btn redBtn regInput submit" value="免费注册 ">
							</li>
						</ul>
					</div>
					<div class="box emailBox">
						<ul>
							<li>
								<p>邮箱</p>
								<input type="text" class="phoneNum regInput yh" placeholder="邮箱地址">
							</li>
							<li>
								<p>密码</p>
								<input type="password" class="pwd regInput yh" placeholder="6-20 位字符">
							</li>
							<li>
								<p>手机号（选填）</p>
								<input type="password" class="pwd regInput yh" placeholder="填写手机号码，第一时间获知名师公开课">
							</li>
							
							<li class="agreementLi">
								<input type="radio" id="agreement">
								<label for="agreement">我已阅读并同意</label>
								<a href="javascript:void(0)">那好网服务使用协议</a>
							</li>
							<li>
								<input type="submit" class="btn redBtn regInput submit" value="免费注册 ">
							</li>
						</ul>
					</div>
				</div>
			</div>			
		</div>
		<div class="goLogin fr">
			<p>已有帐户，点击这里</p>
			<a href="javascript:void(0)" class="btn blueBtn">登录</a>
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