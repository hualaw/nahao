define(function(require,exports){	
	// 公共select模拟
	require('select');
	// 美化select
	$('select').jqTransSelect();
	// 美化radio
	$('input[type=radio]').jqTransRadio();

	var _tab = require("module/common/method/tab");
	var _valid = require("module/studentMyCourse/valid");



//需要判断加载的  等开发完了 加
    // 个人资料 （手机版） 验证
    _valid.phoneForm();
    // 个人资料 （邮箱版） 验证
    _valid.emailForm();
    //基本资料 修改密码验证
    _valid.ichangePWForm();
	// 退课 申请状态 验证
	_valid.applyFrom();


	//tab判断加载 等开发完了以后再加
	// if($("#wrapContent").hasClass("myOrderCon")){
	// 	// 退课 申请状态 验证
	// 	_valid.applyFrom();
	// }
	//我的订单 tab
	_tab.tab($(".tabh li"),"tabhOn",$(".tabCon .tabBox"));
	//基本资料 tab
	_tab.tab($(".inforTab .tabh li"),"inforOn",$(".inforTabBox"));
	//购买后 右侧 tab
	_tab.tab($(".abuyTabh h3"),"curShow",$(".abuyTabBox"));

	var _myCourse = require("module/studentMyCourse/myCourse");





	if($(".buyAfter").length){
		//开课 倒计时
		//_myCourse.countDown();
		//云笔记 弹框
		_myCourse.cNote();
	}else if($(".buyBefore").length){
	    //购买前  选开课时间
		_myCourse.timeToggle();
		//报名 倒计时
		_myCourse.countDown();
	}else{
		// 左侧栏 高亮
		_myCourse.leftNav();
	}
})