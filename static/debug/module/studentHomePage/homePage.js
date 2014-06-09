define(function(require,exports){
	
	//首页页面我的课程跳转
	exports.skip = function (){
		$(".courseBox").on("click", '.rotateBox', function () {
			var url = $(this).data('action');
			window.open(url);
		});		
	}
	
})