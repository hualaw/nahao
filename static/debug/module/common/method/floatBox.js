define(function(require,exports){
	// 悬浮框
    exports.floatBox = function (oDiv,returnBtn){

		window.onload = function (){
			scrollTopfn();
		}
		window.onresize = function (){
			scrollTopfn();
		}
    	window.onscroll=function (){
			scrollTopfn();
		}

		function scrollTopfn(){
			var _left=parseInt($("#nahaoModule").prop("offsetLeft"))+980+13;
			returnBtn.parent(".floatBox").css({"left":_left+"px"}).fadeIn();
			var scrollTop=document.body.scrollTop||document.documentElement.scrollTop;
			//判断ie6
			if (window.navigator.userAgent.indexOf("MSIE 6")!=-1){
				oDiv.style.top=scrollTop+document.documentElement.clientHeight-oDiv.offsetHeight+"px";  
			}
			//判断 返回头部 显示隐藏
			if(scrollTop == 0){
				returnBtn.hide();
			}else{
				returnBtn.css({"display":"inline-block"});
			}
		}
		//威信code hover绑定
		if($(".weixinCode").length){
			$(".weixinCode").hover(function(){
				$(this).children(".wxWrap").addClass("active");
			},function(){
				$(this).children(".wxWrap").removeClass("active");
			});
		}
		//点击返回首部		
		returnBtn.click(function (){
			$("html,body").animate({scrollTop:0});
		})
    }
    //浏览记录悬浮
    exports.historyFloat=function(){
    	var _item=$(".historyWrap"),_target=_item.children(".historyListWrap");
    	var _left=_item.prop("offsetLeft"),_top=_item.prop("offsetTop"),
    	_windowTop=$(window).scrollTop();
    	if(_windowTop>=_top){
    		_target.css({"position":"fixed","top":"0px","left":_left+"px","z-index":"99"});
    	}else{
    		_target.css({"position":"static","z-index":"1"});
    	}
    	_item.children(".historyListWrap");
    }
    //清空浏览记录
    exports.clearHis=function(){
    	//清空浏览记录
	    if($(".historyWrap .deletHistory").length){
	    	$(".historyWrap .deletHistory").click(function(e){
	    		$('.historyList').remove();
	    		$(".historyWrap .historyListWrap").append("<div class=\"noHistory\">暂无浏览记录</div>");
	    		//清除cookie
	    		document.cookie = 'recent_view' + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
	    		e.preventDefault();
	    	});
	    }
    }
})