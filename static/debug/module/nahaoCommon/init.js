define(function(require,exports){
	// 首页导航 高亮
	require('module/common/method/curNav').curNav(".headNav","nahaoModule");
	// 悬浮框
	require('module/common/method/floatBox').floatBox($(".floatBox").get(0),$(".floatBox .returnTop"));
	$(".feedback").click(function (){
    	$.dialog({
    		id:"feedback_close",
            title:false,
            ok:false,
            icon:false,
            padding:0,
            content:$(".feedbackHtml").html()
        });
	    // 教室-意见反馈 验证
		require("module/classRoom/valid").feedbackForm();
	})

	//判断支不支持 transform
	//window.navigator.userAgent.indexOf("MSIE")!=-1  为了 区别火狐
	if($("body").css("WebkitTransform")==undefined&&window.navigator.userAgent.indexOf("MSIE")!=-1){
		if($("#nahaoModule").attr("module")=="studentHomePage"||$("#nahaoModule").attr("module")=="studentMyCourse"){
			$("#nahaoModule").addClass("lowHomePage");
		}
	};
	// 加载模拟select下拉框、radio等
	require('module/lib/select');

	// 加载全站公共登陆脚本
	require('module/nahaoCommon/commonLogin').loginForm();

	if(window.navigator.userAgent.indexOf("MSIE 6")!=-1||window.navigator.userAgent.indexOf("MSIE 7")!=-1){
		if(!$(".iebrowser")){
			$(".header").prepend('<div class="iebrowser">您正在使用的浏览器无法支持那好的正常使用。为更好的浏览本站，建议您将浏览器升级到IE8或以下浏览器：360极速 / Chrome / Safari<span>下载地址：<a href="{$student_url}index/browser">点击这里</a></span></div>');
		}		
	}
})