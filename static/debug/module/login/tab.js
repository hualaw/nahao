define(function(require,exports){
	// 首页导航 高亮
    exports.tab = function (){
    	$(".tabBox a").click(function(){
    		var _index=$(this).index();
    		$(this).addClass("active").siblings("a").removeClass("active");
    		$(".contentBox .box").eq(_index).show().siblings(".box").hide();
    		// 请求focus的时候出现提示文字的样式
        	seajs.use("module/login/validFocus");
    	});
    }
})