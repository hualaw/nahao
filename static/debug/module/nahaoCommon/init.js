define(function(require,exports){
	// 首页导航 高亮
	require('module/common/method/curNav').curNav();
	// 悬浮框
	require('module/common/method/floatBox').floatBox($(".floatBox").get(0),$(".floatBox .returnTop"));

	//判断支不支持 transform
	//window.navigator.userAgent.indexOf("MSIE")!=-1  为了 区别火狐
	if($("body").css("WebkitTransform")==undefined&&window.navigator.userAgent.indexOf("MSIE")!=-1){
		if($("#nahaoModule").attr("module")=="studentHomePage"||$("#nahaoModule").attr("module")=="studentMyCourse"){
			$("#nahaoModule").addClass("lowHomePage");
		}
	};
	// 加载模拟select下拉框、radio等
	require('module/lib/select');
})