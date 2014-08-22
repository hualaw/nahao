//hover提示效果脚本
//author shanghongliang
//date 2014-08-18 14:20
define(function(require,exports){
	exports.init=function(item){
		//tip bind
		item.hover(function(){
			var _item=$(this).children(".tipWrap");
			_item.addClass("active");
			_left=parseInt($(this).children(".tipWrap").prop("offsetWidth"))/2;
			_item.css({"margin-left":"-"+_left+"px"});

		},function(){
			$(this).children(".tipWrap").removeClass("active");
		});
	}
});