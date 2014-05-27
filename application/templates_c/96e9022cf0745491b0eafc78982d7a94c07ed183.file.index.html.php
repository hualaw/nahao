<?php /* Smarty version Smarty-3.1.14, created on 2014-05-27 09:37:57
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\studentStudy\index.html" */ ?>
<?php /*%%SmartyHeaderCode:116495383ec7516e5b0-68688535%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '96e9022cf0745491b0eafc78982d7a94c07ed183' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\studentStudy\\index.html',
      1 => 1401103731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '116495383ec7516e5b0-68688535',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5383ec751ec726_73857050',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5383ec751ec726_73857050')) {function content_5383ec751ec726_73857050($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>首页</title>
<meta name="description" content="" />
<meta name="keywords" content="">
<link rel="stylesheet" type="text/css" href="<?php echo static_url('/css/studentStudy/style.css');?>
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
<div class="wrap layout studentStudy" id="nahaoModule" module="studentStudy">
	主要内容
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