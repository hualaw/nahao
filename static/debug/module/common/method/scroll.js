define(function (require,exporst){
	//公共 滚动条
	exporst.myscroll = function (scrollChild,conChild,conPar){
		var inix=0;

		if (conChild.height()>conPar.height()){
			scrollChild.parent().removeClass("undis");
		}
		scrollChild.height(scrollChild.parent().height()*conPar.height()/conChild.height());

		conPar.mouseover(function (){
			var aa = document.body.scrollTop||document.documentElement.scrollTop;

			// wheelFn(oDiv,function (down){
			// 	var scrollTop = document.body.scrollTop||document.documentElement.scrollTop;
			// 	if (down){
			// 		return;
			// 		console.log(document.body.scrollTop||document.documentElement.scrollTop)
			// 		scrollTop = aa-100;
			// 	}
			// 	else{ 
			// 		return;
			// 		console.log(document.body.scrollTop||document.documentElement.scrollTop)
			// 		scrollTop = aa;
			// 	}
			// });

			addWheel(conPar,function (down){
				var scrollTop = document.body.scrollTop||document.documentElement.scrollTop;
				if (down){
					return;
					scrollTop = aa-100;
					var l=scrollChild.get(0).offsetTop+10;
				}else{
					return;
					scrollTop = aa;
					var l=scrollChild.get(0).offsetTop-10;
				}
				if(l<0){
					l=0;	
				}
				if(l>scrollChild.parent().height()-scrollChild.height()){
					l=scrollChild.parent().height()-scrollChild.height();	
				}
				
				scrollChild.css({"top":l+"px"});
				conChild.css({"top":-l*(conChild.height()-conPar.height())/(scrollChild.parent().height()-scrollChild.height())+"px"});
			});
		})
		
		scrollChild.mousedown(function (e){
			var sx=scrollChild.offset().top-scrollChild.parent().offset().top;
			inix=e.clientY-sx;
			$(document).bind("mousemove",function (e){
				var l=e.clientY-inix;
				if(l<0){
					l=0;	
				}
				if(l>scrollChild.parent().height()-scrollChild.height()){
					l=scrollChild.parent().height()-scrollChild.height();	
				}
				scrollChild.css({"top":l+"px"});
				conChild.css({"top":-l*(conChild.height()-conPar.height())/(scrollChild.parent().height()-scrollChild.height())+"px"});
			})
			$(document).mouseup(function (){
				$(document).unbind("mousemove");
			})
			return false;
		})
	}

	function addWheel(oDiv,fn,ev){
		var oEvent = ev||event;
		if (oDiv.addEventListener){
			oDiv.addEventListener('DOMMouseScroll',fnWheel,false);
		}
		oDiv.onmousewheel=fnWheel;

		function fnWheel(ev){
			var oEvent=ev||event;
			var down=true;//向下
			if (oEvent.wheelDelta){
				down=oEvent.wheelDelta<0;
				}
			else{
				down=oEvent.detail>0;
				}
			fn(down);
			if(oEvent.preventDefault)
			{
				oEvent.preventDefault();
			}
			return false;
			oEvent.cancelBubble = true;
		}
	}
})