define(function(require,exports){
	// 左侧栏 高亮
    exports.leftNav = function (){
    	if($(".menu li").length){
	    	for(var i=0;i<$(".menu li").length;i++){
		        if($(".menu li").eq(i).attr("name").indexOf($("#wrapContent").attr("name"))!=-1){
		        	$(".menu li").removeClass("menuOn");
		        	$(".menu li").eq(i).addClass("menuOn");
		        }
	    	}
    	}
    }

    //云笔记
    exports.cNote = function (){
    	$(".cloudNotes").click(function (){
    		
    	})
    }
    //购买前  选开课时间
    exports.timeToggle = function (){
        $(".enlistForm .ctime").click(function (){
            if($(this).hasClass("ctimeOn")){
                $(this).removeClass("ctimeOn");
            }else{
                $(this).addClass("ctimeOn");
            }
        })
    }
});