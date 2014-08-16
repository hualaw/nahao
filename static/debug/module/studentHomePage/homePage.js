//首页的脚本
//author shanghongliang
//date 2014-08-15 10:39
define(function(require,exports){
	//首页初始化函数
	exports.init=function(){
		//初始化幻灯切换
		exports.bannerInit();
		//初始化首页的标签页
		require("module/common/method/tab_nav").init();
		//直播客silde
		require("module/studentHomePage/slide").init($(".liveLessonWrap"),5000);
		//学员风采slide
		require("module/studentHomePage/slide").init($(".stuListCont"));
		//媒体报道slide
		require("module/studentHomePage/slide").init($(".mediaCont"));
		//屏幕滚动fixed效果
		require("module/common/method/scrollFixed").init();

	}
	//初始化首页的banner
	exports.bannerInit=function(){
		var _banner=function(item,bannerList,orederList,bannerSlide,ms){
			this.item=item;
			this.ms=ms;
			this.bannerList=bannerList;
			this.orederList=orederList;
			this.bannerSlide=bannerSlide;
			this.autoTimer = null;
		}
		_banner.prototype={
			index:function(index){
				var _index=this.orederList.filter(".active").index();
				if((!index&&index!==0)||_index==index){return;}
				var _targetOrder=this.orederList.eq(index),_targetBanner=this.bannerList.eq(index);
				_targetOrder.parent().children().removeClass("active");
				_targetOrder.addClass("active");
				this.bannerList.removeClass("rollshow").stop().animate({opacity:0});
				_targetBanner.addClass("rollshow").stop().animate({opacity:1});
			},
			next:function(){
				var _index=this.orederList.filter(".active").index();
				_index=_index+1;
				if(_index>=this.orederList.length){
					_index=0;
				}
				this.index(_index);
			},
			prev:function(){
				var _index=this.orederList.filter(".active").index();
				_index=_index-1;
				if(_index<0){
					_index=this.orederList.length-1;
				}
				this.index(_index);
			},
			start:function(){
				var _this=this;
				//下面数字标识的hover事件绑定
				this.orederList.hover(function(){
					_this.index($(this).index());
				});
				//整个banner的hover事件绑定
				this.item.hover(function(){
					clearInterval(_this.autoTimer);
				},function(){
					_this.autoTimer = setInterval(function(){
	                 	_this.next();
	            	},_this.ms);
				});
				//清空time
				clearInterval(this.autoTimer);
				//继续循环
				this.autoTimer = setInterval(function(){
	                 _this.next();
	            },this.ms);
	            //左右轮换绑定
	            this.bannerSlide.click(function(){

	            	if($(this).hasClass("next")){
	            		_this.next();
	            	}else{
	            		_this.prev();
	            	}
	            });

			}
		}
		new _banner($("#indexBanner"),$("#indexBanner .rollList li"),$("#indexBanner .rollNav li"),$("#indexBanner .bannerSlide"),5000).start();
	}
})