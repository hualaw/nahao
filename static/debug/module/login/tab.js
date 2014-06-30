define(function(require,exports){
	// 首页导航 高亮
    exports.tab = function (){
    	$(".tabBox a").click(function(){
    		var _index=$(this).index();
    		$(this).addClass("active").siblings("a").removeClass("active");
    		$(".contentBox .box").eq(_index).show().siblings(".box").hide();
    		$(".box:eq("+_index+") form input").eq(0).focus();
    	});
    }
})