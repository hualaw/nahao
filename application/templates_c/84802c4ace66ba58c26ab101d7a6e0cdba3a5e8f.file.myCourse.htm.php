<?php /* Smarty version Smarty-3.1.14, created on 2014-05-26 19:17:10
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\include\myCourse\page\myCourse.htm" */ ?>
<?php /*%%SmartyHeaderCode:28855538322b60b4cd8-45596000%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '84802c4ace66ba58c26ab101d7a6e0cdba3a5e8f' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\include\\myCourse\\page\\myCourse.htm',
      1 => 1401102887,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28855538322b60b4cd8-45596000',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_538322b60cb6a3_57637004',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_538322b60cb6a3_57637004')) {function content_538322b60cb6a3_57637004($_smarty_tpl) {?><div class="courseConList">
	<h3 class="pageName">我的课程</h3>
	<ul class="myCourseList cf">
		<!-- meiyou-->
		<li class="fl noCourse">
			<span><a href="/index/index">快来添加课程吧！</a></span>
		</li>
		<!-- kecheng yi wancheng -->
		<li class="fl hasCourse">
			<h3>2014年五年级奥数暑假训练营</h3>
			<img src="<?php echo static_url('/images/myCourse/cover1.png');?>
" alt="" class="cover">
			<div class="progress">
				<span class="bar"></span>
			</div>
			<div class="courseInfor">
				<div class="lessons">
					<span class="redText">已上9节</span> / 共12
				</div>
				<div class="classTime">
					<p>下节课</p>
					<p class="redText">
						<span>7月4日</span>
						<span>星期一</span>
						<span>09:00-11:00</span>
					</p>
				</div>
			</div>
			<a href="/course/buy_after" class="btn blueBtn">进入课程</a>
		</li>
		<!-- haimei wancheng -->
		<li class="fl hasCourse courseOver">
			<h3>2014年五年级奥数暑假训练营</h3>
			<img src="<?php echo static_url('/images/myCourse/cover1.png');?>
" alt="" class="cover">
			<div class="progress">
				<span class="bar"></span>
			</div>
			<div class="courseInfor">
				<div class="lessons">
					<span>已上12节</span> / 共12
				</div>
				<div class="over">
					已完成
				</div>
			</div>
			<a href="/course/buy_after" class="btn blueBtn">回顾课程</a>
		</li>
	</ul>
</div>
<div class="courseConList newList">
	<h3 class="pageName">最新课程</h3>
	<ul class="cf">
		<!--  xuanzhuan-->
		<li class="fl">
			<div class="clBoxShaow iniBox">
				<img src="<?php echo static_url('/images/myCourse/cover1.png');?>
" alt="">
				<div class="iniInfor">
					<p class="courseTitle">2005北京高考各科考点分析</p>
					<div class="cf">
						<span class="fl courseTime">7月15日－8月15日</span>
						<span class="fr"><em class="redText">1799</em>人</span>
					</div>
					<div class="cf fitGrade">
						<span class="fl">适合4-9年级</span>
						<span class="fr">12课次</span>
					</div>
				</div>
			</div>
			<div class="clBoxShaow rotateBox posr">
				<div class="teaInfor cf">
					<img src="" alt="头像" class="fl">
					<div class="fl teaInforR">
						<!--工作证，称职证，教师证（1-5）-->
						<h3 class="cf">
							<em class="fl">张渐变</em>
							<span class="workCard fl"></span>
							<span class="TitleCard fl"></span>
							<span class="teaCard fl">1</span>
						</h3>
						<p class="detailInfor">北京顶级高中名校高级教师</p>
					</div>
				</div>
				<p class="brief">语文考试中阅读，写作是重中之重，但提升这两部分的关键还在于基础能力的夯实，学好字词句段文才能。</p>
				<p class="courseTitle"><a href="/course/buy_before">2005北京高考各科考点...</a></p>
			</div>
		</li>
	</ul>
</div><?php }} ?>