define(function(require,exports){
	// 首页导航 高亮
    exports.tab = function (){
    	$(".payBox h3 a").click(function(){
    		var _index=$(this).index();
    		$(this).addClass("active").siblings("a").removeClass("active");
    		$(".payBox .box").eq(_index).show().siblings(".box").hide()
    	});
    	$(".onlineBank li").click(function(){
    		var _index=$(this).index();
    		$(".li-table").eq(_index).show().siblings(".li-table").hide();
    	});
    }
})