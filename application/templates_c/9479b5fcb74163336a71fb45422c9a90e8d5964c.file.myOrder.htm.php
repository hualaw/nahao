<?php /* Smarty version Smarty-3.1.14, created on 2014-05-26 19:17:10
         compiled from "D:\wamp\www\nahao\nahao_student_8052\application\views\www\include\myCourse\page\myOrder.htm" */ ?>
<?php /*%%SmartyHeaderCode:28708538322b60de706-89412616%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9479b5fcb74163336a71fb45422c9a90e8d5964c' => 
    array (
      0 => 'D:\\wamp\\www\\nahao\\nahao_student_8052\\application\\views\\www\\include\\myCourse\\page\\myOrder.htm',
      1 => 1401095945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28708538322b60de706-89412616',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_538322b60e4163_69925901',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_538322b60e4163_69925901')) {function content_538322b60e4163_69925901($_smarty_tpl) {?><!--我的订单-->
<!-- <h3 class="pageName">订单详情</h3>
<div class="orderComBox">
	<ul class="tabh cf">
		<li class="tabhOn">全部订单</li>
		<li>已付款<span class="redText">11</span></li>
		<li>未付款<span class="redText">2</span></li>
		<li>已取消<span class="redText">1</span></li>
		<li class="onRBor">已退款<span class="redText">3</span></li>
	</ul>
	<div class="tabCon">
		<div class="tabBox">
			<table>
				<thead>
					<tr>
						<td class="orderName oInfor">订单信息</td>
						<td class="orderName">金额</td>
						<td class="orderName">下单时间</td>
						<td class="orderName">状态</td>
						<td class="orderName">操作</td>
					</tr>
				</thead>
				<tbody>
					<tr class="nopayment">
						<td class="oInfor">
							<p class="ordernum">订单编号<span>87623868273554</span></p>
							<div>
								<img src="" alt="">
								<img src="" alt="">
								<img src="" alt="">
								<img src="" alt="">
								<img src="" alt="">
							</div>
							<p class="orderTitle">应老师 2014暑假作文提升训练</p>
						</td>
						<td><strong class="redText">¥ 199</strong></td>
						<td>
							<span class="time">
								2014／06／23<br>18：58 : 43
							</span>
						</td>
						<td class="redText">未付款</td>
						<td>
							<a href="" class="blueText">查看</a><br>
							<a href="" class="cf btn3 btn">
								<span class="fl">支付</span>
								<span class="fr"></span>
							</a>
						</td>
					</tr>
					<tr class="payment">
						<td class="oInfor">
							<p class="ordernum">订单编号<span>87623868273554</span></p>
							<div>
								<img src="" alt="">
								<img src="" alt="">
							</div>
							<p class="orderTitle">应老师 2014暑假作文提升训练</p>
						</td>
						<td><strong class="redText">¥ 199</strong></td>
						<td>
							<span class="time">
								2014／06／23<br>18：58 : 43
							</span>
						</td>
						<td class="redText">已付款</td>
						<td>
							<a href="" class="blueText">查看</a><br>
							<a href="" class="blueText">申请退课</a>
						</td>
					</tr>
					<tr class="cancle">
						<td class="oInfor">
							<p class="ordernum">订单编号<span>87623868273554</span></p>
							<div>
								<img src="" alt="">
								<img src="" alt="">
							</div>
							<p class="orderTitle">应老师 2014暑假作文提升训练</p>
						</td>
						<td><strong>¥ 199</strong></td>
						<td>
							<span class="time">
								2014／06／23<br>18：58 : 43
							</span>
						</td>
						<td class="state">已取消</td>
						<td>
							<a href="" class="blueText">查看</a><br>
							<a href="" class="blueText">删除</a>
						</td>
					</tr>
					<tr class="refund noBBor">
						<td class="oInfor">
							<p class="ordernum">订单编号<span>87623868273554</span></p>
							<div>
								<img src="" alt="">
								<img src="" alt="">
							</div>
							<p class="orderTitle">应老师 2014暑假作文提升训练</p>
						</td>
						<td><strong>¥ 199</strong></td>
						<td>
							<span class="time">
								2014／06／23<br>18：58 : 43
							</span>
						</td>
						<td class="">已退课</td>
						<td>
							<a href="" class="blueText">查看</a><br>
							<a href="" class="blueText">退课详情</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="undis tabBox">已付款</div>
		<div class="undis tabBox">未付款</div>
		<div class="undis tabBox">已取消</div>
		<div class="undis tabBox">已退款</div>
	</div>
</div> -->

<!--订单详情-->
<!-- <h3 class="pageName">订单详情</h3>
<div class="detailBox">	
	<table class="orderDetail">
		<thead>
			<tr>
				<td>
					<span class="orderName">订单编号</span>
					<span class='redText'>HBDS3289587654</span>
				</td>
				<td colspan="2">
					<span class="orderName">下单时间</span>
					<span class='redText'>2014-07-24  22:17:45</span>
				</td>
				<td>
					<span class="orderName">订单状态</span>
					<span class='redText'>未付款</span>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr class="tName">
				<td>课程名称</td>
				<td>原价</td>
				<td>实际价格</td>
				<td>操作</td>
			</tr>
			<tr class="tCon">
				<td class="spacL">应老师 2014暑假作文提升训练练习</td>
				<td class="iniPrice">¥1099</td>
				<td class="redText">¥599</td>
				<td class="spacR">
					<p>2014-06-23</p>
					<p>18：58 : 43</p>
				</td>
			</tr>
			<tr class="tCon">
				<td class="spacL">应老师 2014暑假作文提升训练练习</td>
				<td class="iniPrice">¥1099</td>
				<td class="redText">¥599</td>
				<td class="spacR">
					<p>2014-06-23</p>
					<p>18：58 : 43</p>
				</td>
			</tr>
			<tr class="tf">
				<td  colspan="4">
					<p>金额总计<strong class="redText">¥1099</strong></p>
					<a href="" class="cf btn3 btn">
						<span class="fl">支付</span>
						<span class="fr"></span>
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</div> -->

<!--申请状态-->
<!-- <h3 class="pageName">申请状态</h3>
<div class="orderComBox">
	<table class="applyState">
		<tbody>
			<--待处理-
			<tr class="imporInfor">
				<td colspan="4" class="talignLeft">
					<span>课程名称</span>
					<strong>应老师 2014暑假作文提升训练</strong>
				</td>
				<td colspan="2" class="talignRight">
					<span>申请时间</span>
					<span>2014-06-23</span>
					<span>18:45:31</span>
				</td>
			</tr>
			<tr class="detailInfor">
				<td class="width_92">
					<span>已上课次</span><br>
					<strong>4</strong>
				</td>
				<td class="width_90">
					<span>未开始课次</span><br>
					<strong>4</strong>
				</td>
				<td class="width_100">
					<span>课程总金额</span><br>
					<strong>¥199</strong>
				</td>
				<td class="width_130">
					<span>可退金额</span><br>
					<strong>¥199</strong>
				</td>
				<td class="reasonTd">
					<span>退课理由</span>
					<p class="reason">对老师不满意，想换一个老师的课程可啊生活费客户韩国司法局后...</p>
				</td>
				<td class="stateTd">
					<span>申请状态</span><br>
					<strong class="redText">待处理</strong>
				</td>
			</tr>
			<--已退款--
			<tr class="imporInfor">
				<td colspan="4" class="talignLeft">
					<span>课程名称</span>
					<strong>应老师 2014暑假作文提升训练</strong>
				</td>
				<td colspan="2" class="talignRight">
					<span>申请时间</span>
					<span>2014-06-23</span>
					<span>18:45:31</span>
				</td>
			</tr>
			<tr class="detailInfor">
				<td class="width_92">
					<span>已上课次</span><br>
					<strong>4</strong>
				</td>
				<td class="width_90">
					<span>未开始课次</span><br>
					<strong>4</strong>
				</td>
				<td class="width_100">
					<span>课程总金额</span><br>
					<strong>¥199</strong>
				</td>
				<td class="width_130">
					<span>可退金额</span><br>
					<strong>¥199</strong>
				</td>
				<td class="reasonTd">
					<span>退课理由</span>
					<p class="reason">对老师不满意，想换一个老师的课程可啊生活费客户韩国司法局后...</p>
				</td>
				<td class="stateTd">
					<span>申请状态</span><br>
					<strong class="greenText">已退款</strong>
				</td>
			</tr>
		</tbody>
	</table>
</div> -->

<!--申请退课-->
<!-- <div class="orderComBox applyFromBox">
	<h3 class="pageName">申请状态
		<span class="redText">温馨提示：您已经完成大半课次，建议学完，请慎重选择是否退课</span>
	</h3>
	<form action="" method="post" onsubmit="return false;" class="applyFrom">
		<ul>
			<li>
				<p class="formName">课程名称</p>
				<input type="text">
				<ol class="classDetail cf">
					<li>
						<span>已上课次</span><br>
						<strong>3 次</strong>
					</li>
					<li>
						<span>未开始课次</span><br>
						<strong>3 次</strong>
					</li>
					<li>
						<span>课程总金额</span><br>
						<strong>¥199</strong>
					</li>
					<li class="formOn">
						<span>可退金额</span><br>
						<strong>¥199</strong>
					</li>
				</ol>
				<p class="detailText">（未开始课次的退款，需在开班前1天（自然日）18：00前，方可申请）</p>
			</li>
			<li>
				<p class="formName">姓名</p>
				<input type="text">
			</li>
			<li>
				<p class="formName">联系方式</p>
				<input type="text">
			</li>
			<li>
				<p class="formName">退课理由</p>
				<textarea name="" id="" ></textarea>
			</li>
			<li>
				<h3 class="pageName">绑定银行帐户</h3>
			</li>
			<li>
				<p class="formName">选择退款银行</p>
				<select name="" id=""></select>
				<input type="text">
			</li>
			<li>
				<p class="formName">银行卡账号</p>
				<input type="text">
			</li>
			<li>
				<p class="formName">身份证号</p>
				<input type="text">
			</li>
			<li>
				<input type="submit" class="btn redBtn" value="提交">
				<input type="button" class="btn blueBtn" value="取消">
			</li>
		</ul>
	</form>
</div> -->

<!--申请成功-->
<!-- <div class="applysuc">
	<p class="pt">提交申请成功！</p>
	<p class="pf">我们会在48小时内处理您的申请，请耐心等待！</p>
</div> -->

<!--申请成功--><?php }} ?>