define(function(require,exports){
	// 悬浮框
    exports.floatBox = function (oDiv,returnBtn){
    	window.onload=window.onscroll=window.onresize=function (){
			var scrollTop=document.body.scrollTop||document.documentElement.scrollTop;
			//判断ie6
			if (window.navigator.userAgent.indexOf("MSIE 6")!=-1){
				oDiv.style.top=scrollTop+document.documentElement.clientHeight-oDiv.offsetHeight+"px";  
			}
			//判断 返回头部 显示隐藏
			if(scrollTop == 0){
				returnBtn.hide();
			}else{
				returnBtn.show();
			}
			//点击返回首部		
			returnBtn.click(function (){
				$("html,body").animate({scrollTop:0});
			})
		}
    }
})