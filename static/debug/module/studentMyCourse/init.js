define(function(require,exports){	
	var _tab = require("module/common/method/tab");
	//我的订单 tab
	_tab.tab($(".tabh li"),"tabhOn",$(".tabCon .tabBox"));
	//基本资料 tab
	_tab.tab($(".inforTab .tabh li"),"inforOn",$(".inforTabBox"));
	//购买后 右侧 tab
	_tab.tab($(".abuyTabh h3"),"curShow",$(".abuyTabBox"));

	var _myCourse = require("module/studentMyCourse/myCourse");
	// 左侧栏 高亮
	_myCourse.leftNav();
    //购买前  选开课时间
	_myCourse.timeToggle();
})