//滚动fixed效果
//author shanghongliang
//date 2014-08-15 10：29
define(function(require,exports){
	//fixedinit初始化
	exports.init=function(){
		//首页课程列表的fixed
		exports.fiexed($(".cListWrap .chTitle"));
		//首页广告区域的fixed
		exports.fiexed($(".historyWrap .historyListWrap"));
	},
	exports.fiexed=function(item,top){
		if(!top){
			top=parseInt($(item).parent().prop("offsetTop"));
		}
		var _left=parseInt($(item).parent().prop("offsetLeft"));
		$(window).scroll(function(){
			var _windowTop=$(window).scrollTop();
			if(_windowTop>=top){
				$(item).css({"position":"fixed","top":"0px;","left":_left+"px","z-index":"1111"});
			}else{
				$(item).css({"position":"absolute","top":"0px;","left":"0px","z-index":"1"});
			}
			var scrollTop=document.body.scrollTop||document.documentElement.scrollTop;
		});
	}

});