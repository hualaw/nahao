define(function (require,exports){
	//做题选答案 (加背景色)
	exports.options = function (){
		$(".answerList li").click(function (){
			$(".answerList li").removeClass("curAnswer");
			$(this).addClass("curAnswer");
		})
	}
	//选择练习题  左右点击切换 题目选中
	exports.itemClick = function (){
		var iniW = $(".itemTabList").eq(0).outerWidth(true),
			iniH = $(".itemTabList").eq(0).outerHeight(true),
			ind = 0;
		//初始高度	
		$(".itemTabBox").height(iniH);

		//选题
		$(".itemTabList").click(function (){
			var _this = $(".itemTabList").index($(this));
			if($(this).hasClass("itemOn")){
				$(this).removeClass("itemOn");
				$(".Titem a").eq(_this).removeClass("titemOn");
				$(".itemNum").html($(".itemNum").html()-1);
            }else{
				$(this).addClass("itemOn");
				$(".Titem a").eq(_this).addClass("titemOn");
				$(".itemNum").html(($(".itemNum").html()-"")+1);
            }
		})
		//左右切换
		function roll(ind){
			$(".itemTabBox").height($(".itemTabList").eq(ind).outerHeight(true));
			$(".itemTabBox ul").stop().animate({left:-ind*iniW});
		}
		$(".clickL").click(function (){
			ind--;
			if(ind<0){
				ind = 0;
			}
			roll(ind);
		});
		$(".clickR").click(function (){
			ind++;
			if(ind>$(".itemTabList").length-1){
				ind = $(".itemTabList").length-1;
			}
			roll(ind);
		});
		$(".Titem a").click(function (){
			roll($(".Titem a").index($(this)));
		})
	}
})