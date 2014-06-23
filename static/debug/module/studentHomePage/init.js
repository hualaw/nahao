define(function(require,exports){
	//课程列表跳转
	require("module/studentHomePage/homePage").skip();
	//大图轮播
	require("module/studentHomePage/homePage").roll();
	
	if(window.navigator.userAgent.indexOf("MSIE")!=-1){
		$(".studentHomePage .ad").addClass("ieadBgc");
	}
})
