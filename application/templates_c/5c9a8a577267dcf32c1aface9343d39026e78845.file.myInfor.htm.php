<?php /* Smarty version Smarty-3.1.14, created on 2014-05-27 09:36:25
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\include\studentMyCourse\page\myInfor.htm" */ ?>
<?php /*%%SmartyHeaderCode:49835383ec195ea333-48881431%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5c9a8a577267dcf32c1aface9343d39026e78845' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\include\\studentMyCourse\\page\\myInfor.htm',
      1 => 1401095945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '49835383ec195ea333-48881431',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5383ec195eec54_87577521',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5383ec195eec54_87577521')) {function content_5383ec195eec54_87577521($_smarty_tpl) {?><!--基本资料-->
<div class="inforTab">
	<ul class="tabh cf">
		<li class="inforOn">个人资料</li>
		<li>修改头像</li>
		<li>修改密码</li>
	</ul>
	<div class="inforTabBox">
		<!--邮箱版-->
		<!-- <form action="" method="post" onsubmit="return false;" class="emailForm">
			<p class="formName">邮箱
				<span class="verWay">zhangyu754@gmail.com</span>
				<span class="hasVer">（已激活）</span>
			</p>
			<ul>
				<li>
					<p class="formName"><span>*</span>昵称</p>
					<input type="text">
				</li>
				<li>
					<p class="formName"><span>*</span>地区</p>
					<select name="" id=""></select>
				</li>
				<li>
					<p class="formName"><span>*</span>邮箱</p>
					<input type="text">
				</li>
				<li>
					<p class="formName"><span>*</span>年级</p>
					<select name="" id=""></select>
					<p class="detailText">提示：那好每年7月1日自动为您更新年纪，如果信息有误，请您及时调整</p>
				</li>
				<li>
					<p class="optional">真实姓名</p>
					<input type="text">
				</li>
				<li>
					<p class="optional">性别</p>
					<input type="radio" name="sex" value="male" class="radio"/> 男
					<input type="radio" name="sex" value="female" class="radio"/> 女
				</li>
				<li>
					<p class="optional">手机号</p>
					<input type="text">
				</li>
				<li class="phoneVer">
					<p class="optional">手机验证码</p>
					<input type="text">
					<input type="button" value="免费获取验证码" class="getVerCode btn">
				</li>
				<li class="attent">
					<p class="optional">关注学科</p>
					<input type="button" value="语文" class="blueBtn btn">
					<input type="button" value="物理" class="blueBtn btn">
					<input type="button" value="兴趣特长" class="blueBtn btn">
					<input type="button" value="化学" class="blueBtn btn">
					<input type="button" value="生物" class="blueBtn btn">
					<input type="button" value="数学" class="blueBtn btn">
					<input type="button" value="英语" class="blueBtn btn">
					<input type="button" value="人文文化" class="blueBtn btn">
					<p class="attNote">注：最多可选择3项</p>
				</li>
				<li>
					<p class="optional">就读学校</p>
					<input type="text">
				</li>
				<li>
					<input type="submit" class="btn redBtn" value="保存">
				</li>
			</ul>
		</form> -->
		<!--手机版-->
		<form action="" method="post" onsubmit="return false;" class="phoneForm">
			<p class="formName">手机号
				<span class="verWay">13786547355</span>
				<span class="hasVer">（已验证）</span>
			</p>
			<ul>
				<li>
					<p class="formName"><span>*</span>昵称</p>
					<input type="text">
				</li>
				<li>
					<p class="formName"><span>*</span>地区</p>
					<select name="" id=""></select>
				</li>
				<li>
					<p class="formName"><span>*</span>邮箱</p>
					<input type="text">
				</li>
				<li>
					<p class="formName"><span>*</span>年级</p>
					<select name="" id=""></select>
					<p class="detailText">提示：那好每年7月1日自动为您更新年纪，如果信息有误，请您及时调整</p>
				</li>
				<li>
					<p class="optional">真实姓名</p>
					<input type="text">
				</li>
				<li>
					<p class="optional">性别</p>
					<input type="radio" name="sex" value="male" class="radio"/> 男
					<input type="radio" name="sex" value="female" class="radio"/> 女
				</li>
				<li class="attent">
					<p class="optional">关注学科</p>
					<input type="button" value="语文" class="blueBtn btn">
					<input type="button" value="物理" class="blueBtn btn">
					<input type="button" value="兴趣特长" class="blueBtn btn">
					<input type="button" value="化学" class="blueBtn btn">
					<input type="button" value="生物" class="blueBtn btn">
					<input type="button" value="数学" class="blueBtn btn">
					<input type="button" value="英语" class="blueBtn btn">
					<input type="button" value="人文文化" class="blueBtn btn">
					<p class="attNote">注：最多可选择3项</p>
				</li>
				<li>
					<p class="optional">就读学校</p>
					<input type="text">
				</li>
				<li>
					<input type="submit" class="btn redBtn" value="保存">
				</li>
			</ul>
		</form>
	</div>
	<div class="inforTabBox undis">
		修改头像
	</div>
	<div class="inforTabBox undis">
		<!--修改密码-->
		<form action="" method="post" onsubmit="return false;" class="changePWForm">
			<ul>
				<li>
					<p class="formName"><span>*</span>当前密码</p>
					<input type="password">
				</li>
				<li>
					<p class="formName"><span>*</span>新密码</p>
					<input type="password">
				</li>
				<li>
					<p class="formName"><span>*</span>确认新密码</p>
					<input type="password">
				</li>
				<li>
					<input type="submit" class="btn redBtn" value="保存">
				</li>
			</ul>
		</form>
	</div>
</div><?php }} ?>