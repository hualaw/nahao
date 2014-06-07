define(function(require,exports){
	exports.skip = function (){
		var aHref = [];
		for(var i=0;i<aHref.length;i++){
			$(".courseList .rotateBox").eq(i).click(function (){
				var _index = $(".courseList .rotateBox").index($(this));
				window.open(aHref[_index]);
			})
		}		
	}
})