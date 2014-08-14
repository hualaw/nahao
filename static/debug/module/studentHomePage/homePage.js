define(function(require,exports){
	//首页初始化函数
	exports.init=function(){
		//初始化幻灯切换
		//require("module/studentHomePage/slide").init();
		//初始化首页的标签页
		require("module/studentHomePage/tab_nav").init();
		exports.bannerInit();
	}
	//初始化首页的banner
	exports.bannerInit=function(){
		var _banner=function(item,bannerList,orederList,time){
			this.item=item;
			this.time=time;
			this.bannerList=bannerList;
			this.orederList=orederList;
		}
		_banner.prototype={
			index:function(index){
				var _index=orederList.filter(".active").index();
				console(_index);
				if((!index&&index!==0)||_index==index){return;}
				var _targetOrder=this.bannerList.eq(index),_targetBanner=this.bannerList.eq(index);
				console.log(_targetOrder);
				console.log(_targetBanner);
			},
			next:function(){

			},
			prev:function(){

			},
			pause:function(){

			},
			start:function(){
				var _this=this;
				this.orederList.mouseover(function(){
					_this.index($(this).index());
				});
			}
		}
		new _banner($("#indexBanner"),$("#indexBanner .rollList li"),$("#indexBanner .rollNav li"),5000).start();
	}
})