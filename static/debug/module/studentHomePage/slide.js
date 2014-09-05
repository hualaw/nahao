//幻灯切换脚本
//author shanghongliang
//date 2014-08-13 19:55
define(function(require,exports){
	//幻灯切换init初始化
	exports.init=function(item,ms){
		var _slide=function(item,ms){
			this.item=item;
			this.bannerOrder=item.find(".bannerOrder");
			this.view=item.find(".bannerView");
			this.bannerSlide=item.find(".bannerSlide");
			this.itemWidth=this.view.find("li").prop("clientWidth");
			this.screenIndex=1;//当前屏的index
			this.totalNum=0;//总屏数
			this.itemLen=3;//一屏默认3个
			this.iWidth=0;//一个元素默认0px宽度
			this.autoTimer=null;
			if(ms){
				this.ms=ms;
			}
		}
		_slide.prototype={
			//开始初始化
			start:function(){
				//console.log(this.innerWrap);
				var _bItem=this.view.find(".bItem");
				//一屏有多少个
				var _viewWidth=this.view.prop("clientWidth"),_itemWidth=_bItem.prop("clientWidth"),
				_num=Math.ceil(parseInt(_viewWidth)/parseInt(_itemWidth));
				this.itemLen=_num;
				//一共有多少屏
				var _len=_bItem.length,_count=Math.ceil(_len/_num);
				this.totalNum=_count;
				//一屏需要移动多少像素
				this.iWidth=_itemWidth;
				if(!this.totalNum||this.totalNum<=1){
					this.bannerSlide.hide();
				}
				var _this=this;
				if(this.totalNum>1){
					var _list=this.view.find("ul");
					_list.parent().append(_list.clone());
					if(this.totalNum==2&&_len<this.totalNum*this.itemLen){
						_list.parent().append(_list.clone());
					}
				}
				this.bannerSlide.click(function(){
					if($(this).hasClass("next")){
						_this.next();
					}else{
						_this.prev();
					}
				});
				if(this.bannerOrder.length&&this.totalNum>1){
					var _htmlArray=[];
					for(var i=0;i<this.totalNum;i++){
						if(i==0){
							_htmlArray.push("<li class=\"fr active\"></li>");
						}else{
							_htmlArray.push("<li class=\"fr\"></li>");
						}
					}
					this.bannerOrder.html(_htmlArray.join(""));
					this.bannerOrder.children("li").hover(function(){
						clearInterval(_this.autoTimer);
						var _index=parseInt($(this).index())+1;
						$(this).parent().children("li").removeClass("active");
						$(this).addClass("active");
						_this.index(_index);
					},function(){
						if(_this.ms&&_this.totalNum>1){
							_this.autoTimer=setInterval(function(){
			                 	_this.next();
			            	},_this.ms);
						}
					});
				}

				if(this.ms&&this.totalNum>1){
					this.bannerSlide.hover(function(){
						clearInterval(_this.autoTimer);
					},function(){
						clearInterval(_this.autoTimer);
						_this.autoTimer=setInterval(function(){
		                 	_this.next();
		            	},_this.ms);
					});
					this.view.hover(function(){clearInterval(_this.autoTimer);},function(){
						clearInterval(_this.autoTimer);
						_this.autoTimer=setInterval(function(){
		                 	_this.next();
		            	},_this.ms);
					});
					clearInterval(_this.autoTimer);
					_this.autoTimer=setInterval(function(){
	                 	_this.next();
	            	},_this.ms);
				}
			},
			//获取某一屏
			index:function(index){
				if(index&&index==this.screenIndex||(index==1&&this.screenIndex==this.totalNum+1)){return;}
				if(index){
					if(this.screenIndex==this.totalNum+1&&index==1){

					}
					this.screenIndex=index;
				}
				var _sLeft=(this.screenIndex-1)*(this.iWidth*this.itemLen);
				this.view.stop().animate({"scrollLeft":_sLeft});
			},
			//下一屏
			next:function(){
				this.screenIndex=this.screenIndex+1;
				if(this.screenIndex>(this.totalNum+1)){
					this.view.prop("scrollLeft",0);
					this.screenIndex=2;
				}
				if(this.bannerOrder.length){
					var _activeIndex=this.screenIndex-1;
					if(this.screenIndex>this.totalNum){
						_activeIndex=0;
					}
					this.bannerOrder.children("li").removeClass("active");
					this.bannerOrder.children("li").eq(_activeIndex).addClass("active");
				}
				this.index();
			},
			//上一屏
			prev:function(){
				this.screenIndex=this.screenIndex-1;
				if(this.screenIndex<1){
					this.view.prop("scrollLeft",(this.totalNum+1)*(this.iWidth*this.itemLen));
					this.screenIndex=this.totalNum;
				}
				if(this.bannerOrder.length){
					var _activeIndex=this.screenIndex-1;
					if(this.screenIndex<0){
						_activeIndex=this.totalNum-1;
					}
					this.bannerOrder.children("li").removeClass("active");
					this.bannerOrder.children("li").eq(_activeIndex).addClass("active");
				}
				this.index();
			}
		}
		var _slideObj=new _slide(item,ms);
		_slideObj.start();
		return _slideObj;
	}
});