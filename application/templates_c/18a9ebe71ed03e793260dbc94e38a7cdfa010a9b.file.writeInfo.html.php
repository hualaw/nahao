<?php /* Smarty version Smarty-3.1.14, created on 2014-05-26 19:16:20
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\startClass\writeInfo.html" */ ?>
<?php /*%%SmartyHeaderCode:1533553832284263df4-57572281%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '18a9ebe71ed03e793260dbc94e38a7cdfa010a9b' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\startClass\\writeInfo.html',
      1 => 1401102770,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1533553832284263df4-57572281',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_538322842ec874_55120972',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_538322842ec874_55120972')) {function content_538322842ec874_55120972($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>首页</title>
<meta name="description" content="" />
<meta name="keywords" content="">
<link rel="stylesheet" type="text/css" href="<?php echo static_url('/css/startClass/style.css');?>
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
<div class="wrap  startClass" id="nahaoModule" module="startClass">
	<div class="writeInfo">
		<div class="box layout">
			<h2>请填写试讲信息<span>(以下信息会严格保密，请您放心填写）</span></h2>
			<form action="" mothed="post" onsubmit="return false">
			<ul class="Oul">
				<li class="cf">
					<p class="fl">真实姓名</p>
					<input type="text" class="fl textInput">
					<p class="tips fl">真实姓名</p>
				</li>
				<li class="cf">
					<p class="fl">所在地区</p>
					<select name="" id="" class="fl">
						<option value="">省份</option>
						<option value="1">省份</option>
					</select>
					<select name="" id="" class="fl">
						<option value="">城市</option>
						<option value="1">城市</option>
					</select>
					<select name="" id="" class="fl">
						<option value="">区</option>
						<option value="1">区</option>
					</select>
				</li>
				<li class="cf">
					<p class="fl">选择性别</p>
					<input type="radio" name="sex" id="man" class="fl radioInput">
					<label for="man" class="fl">男</label>
					<input type="radio" name="sex" id="woman" class="fl radioInput">
					<label for="woman" class="fl">女</label>
				</li>
				<li class="cf">
					<p class="fl">教学阶段</p>
					<input type="checkbox" name="sex" id="high" class="fl radioInput">
					<label for="high" class="fl">高中</label>
					<input type="checkbox" name="sex" id="middle" class="fl radioInput">
					<label for="middle" class="fl">初中</label>
					<input type="checkbox" name="sex" id="small" class="fl radioInput">
					<label for="small" class="fl">小学</label>
				</li>
				<li class="cf">
					<p class="fl">所在学校</p>
					<input type="text" class="fl textInput">
				</li>
				<li class="cf">
					<p class="fl">教师职称</p>
					<select name="" id="" class="fl">
						<option value="">请选择职称</option>
						<option value="1">五年级</option>
					</select>								
				</li>
				<li class="cf">
					<p class="fl">实际教龄</p>
					<select name="" id="" class="fl">
						<option value="">请选择教龄</option>
						<option value="1">2</option>
					</select>
					<p class="tips fl">选择正确年级，专属活动等着你</p>			
				</li>
				<li class="cf">
					<p class="fl">手机号码</p>
					<input type="text" class="fl textInput">
					<p class="tips fl">保密，方便我们与您电话沟通详细情况</p>	
				</li>
				<li class="cf">
					<p class="fl">常用邮箱</p>
					<input type="text" class="fl textInput">	
				</li>
				<li class="cf">
					<p class="fl">QQ号</p>
					<input type="text" class="fl textInput">	
				</li>
				<li class="cf">
					<p class="fl">讲课方式</p>
					<select name="" id="" class="fl">
						<option value="">请选择讲课方式</option>
						<option value="1">2</option>
					</select>		
				</li>
				<li class="cf">
					<p class="fl">试讲科目</p>
					<select name="" id="" class="fl">
						<option value="">请选择试讲科目</option>
						<option value="1">2</option>
					</select>		
				</li>
				<li class="cf cookedTime">
					<p class="fl">预约时间</p>
					<input type="text" class="fl textInput">
					<select name="" id="" class="fl">
						<option value="">选择开始时间</option>
						<option value="1">2</option>
					</select>
					<span class="fl underLine">—</span>
					<select name="" id="" class="fl">
						<option value="">选择结束时间</option>
						<option value="1">2</option>
					</select>	
				</li>
				<li class="cf">
					<p class="fl">课程介绍</p>
					<textarea id="postEditor" style="visibility: hidden;"></textarea>		
				</li>
				<li class="cf">
					<input type="submit" value="下一步" class="btn redBtn submit fl">
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