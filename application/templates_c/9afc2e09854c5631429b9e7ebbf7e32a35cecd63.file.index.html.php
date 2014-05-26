<?php /* Smarty version Smarty-3.1.14, created on 2014-05-26 19:17:09
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\myCourse\index.html" */ ?>
<?php /*%%SmartyHeaderCode:1144538322b5f16ea0-95214175%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9afc2e09854c5631429b9e7ebbf7e32a35cecd63' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\myCourse\\index.html',
      1 => 1401102768,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1144538322b5f16ea0-95214175',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_538322b605d2b6_61823306',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_538322b605d2b6_61823306')) {function content_538322b605d2b6_61823306($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>我的课程</title>
<meta name="description" content="" />
<meta name="keywords" content="">
<link rel="stylesheet" type="text/css" href="<?php echo static_url('/css/myCourse/style.css');?>
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
<div class="wrap layout myCourse" id="nahaoModule" module="myCourse">
	<!-- 右侧框架开始 -->

    <!--  myCourseCon    myOrderCon   myInforCon -->
    <div class="wrapContent fr myInforCon" id="wrapContent" name="myInforCon">
        <!--我的课程-->
        <!--#include virtual="/include/myCourse/page/myCourse.htm"-->
        <?php echo $_smarty_tpl->getSubTemplate ("www/include/myCourse/page/myCourse.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


        <!--我的订单-->
        <!--include virtual="/include/myCourse/page/myOrder.htm"-->
        <?php echo $_smarty_tpl->getSubTemplate ("www/include/myCourse/page/myOrder.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

        
        <!--基本资料-->
        <!--include virtual="/include/myCourse/page/myInfor.htm" -->
        <?php echo $_smarty_tpl->getSubTemplate ("www/include/myCourse/page/myInfor.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    </div> 
    <!-- 右侧框架结束 -->
    <!-- 左侧框架开始 -->
    <div class="slide fl" id="slide">
		<!--#include virtual="/include/myCourse/myCourseLeft.htm"-->
		<?php echo $_smarty_tpl->getSubTemplate ("www/include/myCourse/myCourseLeft.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    </div>
    <!-- 左侧框架结束 -->
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