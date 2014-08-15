//幻灯切换脚本
//author shanghongliang
//date 2014-08-13 19:55
define(function(require,exports){
	var _viewWidth=570;_interval=3000,_indexList=$(".indexlist li"),_bannerList=$(".bannerScroll .rollList li");
	//幻灯切换初始函数
	exports.init=function(indexList,bannerList){
		if(indexList){
			_indexList=indexList;
		}
		if(bannerList){
			_bannerList=bannerList;
		}
		
		_indexList.mouseover(function(){
			exports.index($(this).index());
		});
		$(".bannerScroll .bannerSlide").click(function(){

			var _index=$(".indexlist li").filter(".active").index();
			if($(this).hasClass("prev")){
				exports.prev(_index);
			}else{
				exports.next(_index);
			}
		});
	}
	//根据index进行切换幻灯
	exports.index=function(index){
		if(!index&&index!==0){return;}
		var _target=_indexList.eq(index),_targetBanner=_bannerList.eq(index);
		_target.parent().children().removeClass("active");
		_target.addClass("active");
		_targetBanner.parent().children().stop().removeClass("rollshow").animate({opacity:0});
	    _targetBanner.stop().animate({opacity:1}).addClass("rollshow");
	}
	//下一张幻灯
	exports.next=function(index){
		index=index+1;
		if(index>(_indexList.length-1)){
			index=0;
		}
		exports.index(index);
	}
	//上一张幻灯
	exports.prev=function(index){
		index=index-1;
		if(index<0){
			index=(_indexList.length-1);
		}
		exports.index(index);
	}
	//暂停幻灯切换
	exports.pause=function(){

	}
});