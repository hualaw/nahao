<?php /* Smarty version Smarty-3.1.14, created on 2014-05-26 19:17:10
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\include\header.htm" */ ?>
<?php /*%%SmartyHeaderCode:7094538322b609f9a3-30715778%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '139beed8c3c23bae63f7744ac28800829d582f63' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\include\\header.htm',
      1 => 1401095945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7094538322b609f9a3-30715778',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_538322b60a2cc6_28587901',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_538322b60a2cc6_28587901')) {function content_538322b60a2cc6_28587901($_smarty_tpl) {?><div class="header">
	<div class="headLogin">
		<div class="layout headInfor">
			<a href="javascript:vold(0);" class="fl phoneHref">400-123-1122</a>
			<div class="fr headLoginBox">
				<!--登录之前 开始-->
				<div class="loginBefor ">
					<a href="/index/login" class="blueText">登录</a>
					<span>|</span>
					<a href="/index/register" class="blueText">注册</a>
				</div>
				<!--登录之前 结束-->
				<!--登录之后 开始-->
				<div class="loginAfter undis">
					<a href="" class="blueText">pine小丸子</a>
					<span>|</span>
					<a href="" class="grayText">退出</a>
				</div>
				<!--登录之后 结束-->
			</div>
			<a href="/index/apply_teach" class="fr startClass">我要开课</a>
		</div>
	</div>
	<div class="headNav">
		<div class="layout">
			<!--那好 logo-->
			<h1 class="fl"><img src="" alt=""></h1>
			<!--那好 nav-->
			<ul class="fl">
				<li class="fl homePage"><a href="/index/index">首页</a></li>
				<li class="fl myCourse"><a href="/course/index">我的课程</a></li>
				<!-- <li class="fl startClass"><a href="">我要开课</a></li> -->
				<li class="fl study"><a href="/index/study">我要学习</a></li>
			</ul>
			<!--那好 cart-->
			<!-- <div class="headCart fr">
				<span class="redText cartBg">13</span>
				<a href="" class="redBtn">去购物车结算</a>
			</div> -->
		</div>
	</div>
</div><?php }} ?>