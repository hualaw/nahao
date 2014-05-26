define(function(require,exports){
	// tab
    exports.tab=function(clickObj,className,showObj){
	    clickObj.click(function (){
	        var index = clickObj.index($(this));

	        clickObj.removeClass(className);
	        $(this).addClass(className);
	        showObj.addClass("undis");
	        showObj.eq(index).removeClass("undis");

	    });
	};
})