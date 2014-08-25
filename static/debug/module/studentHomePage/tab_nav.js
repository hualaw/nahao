define(function(require,exports){
	//tab标签页绑定
	exports.init=function(){
		$(".list_tabs .tabItem").click(function(e){
			var _index=$(this).index(),_link=$(this).attr("link");
			$(this).parent().children().removeClass("active");
			$(this).addClass("active");
			var _item=$(this).parents(".list_tabs").find(".tabContent");
			_item.children().removeClass("active");
			_item.children(".item[itemname="+_link+"]").addClass("active");
			e.preventDefault();
		});
	}
	
})