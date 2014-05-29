define(function(require,exports){
	// 首页导航 高亮
    exports.curNav = function (){
    	if($(".headNav li").length){
	    	for(var i=0;i<$(".headNav li").length;i++){
		        if($(".headNav li").eq(i).attr("class").indexOf($("#nahaoModule").attr("module"))!=-1){
		        	$(".headNav li").removeClass("curNav");
		        	$(".headNav li").eq(i).addClass("curNav");
		        }
	    	}
    	}
    }
})