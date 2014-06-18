define(function(require,exports){
	// 首页导航 高亮
    exports.curNav = function (obj,id){
    	if($(obj+" li").length){
	    	for(var i=0;i<$(obj+" li").length;i++){
		        if($(obj+" li").eq(i).attr("class").indexOf($("#"+id).attr("module"))!=-1){
		        	$(obj+" li").removeClass("curNav");
		        	$(obj+" li").eq(i).addClass("curNav");
		        }
	    	}
    	}
    }
})