define(function(require,exports){	
	// 公共select模拟
	require('select');
	// 美化radio
	$('input[type=radio]').jqTransRadio();

	var _tab = require("module/common/method/tab");
	var _valid = require("module/studentMyCourse/valid");

	var _myCourse = require("module/studentMyCourse/myCourse");

	// 选择学校组件
	require('module/common/method/setSchool');

	if($("#wrapContent").hasClass("myOrderCon")){
		// 退课 申请状态 验证
		_valid.applyFrom();
		//我的订单 tab
		_tab.tab($(".tabh li"),"tabhOn",$(".tabCon .tabBox"));
	}
	if($("#wrapContent").hasClass("myInforCon")){
		//基本资料 tab
		_tab.tab($(".inforTab .tabh li"),"inforOn",$(".inforTabBox"));
		
	    //基本资料 修改密码验证
	    _valid.ichangePWForm();
	    // 个人资料 （手机版） 验证
	    _valid.phoneForm();
	    // 个人资料 （邮箱版） 验证
	    _valid.emailForm();
	    //发送手机验证码
	    _myCourse.sendValidateCode();
    	//修改头像 定位
	    _myCourse.changedHead();
	}
	if($("#wrapContent").hasClass("myCourseCon")){
	    //最新课程页面跳转
	    _myCourse.new_class_skip();
	}

	if($(".buyAfter").length){
		if($(".manInfor").height()>179){
			$(".manInfor").css({"overflow-y":"scroll","height":"179px"});
		}
		//购买后 右侧 tab
		_tab.tab($(".abuyTabh h3"),"curShow",$(".abuyTabBox"));
		//云笔记 弹框
		_myCourse.cNote();
	    //我的课程购买之后 列表 课程回顾 背景圆(包括评价)
	    _myCourse.overCourse();
		//开课 倒计时
		_myCourse.countDown($(".classCDcon"),"stime",2);
	}else if($(".buyBefore").length){
	    //购买前  选开课时间
		_myCourse.timeToggle();
		//报名 倒计时
		_myCourse.countDown($(".countdown"),"sell_endtime",1);
		//购买前--点击立即购买
		_myCourse.soon_buy();
		//购买前下面--点击购买课程
		_myCourse.soon_buy_xia();
		//购买前分享
		require('module/common/method/share').shareInsertBg();
	}else{
		// 左侧栏 高亮
		_myCourse.leftNav();
		//我的订单列表删除
		_myCourse.doDelMyOrder();
		//我的订单列表取消
		_myCourse.doCancelMyOrder();
	}
	
    //个人资料修改地区
    if($("#province").length > 0) {
        require("module/studentMyCourse/area").change_area();
    }
})
