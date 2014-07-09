define(function(require,exports){
	
	//首页页面我的课程跳转
	exports.skip = function (){
		$(".courseBox").on("click", '.rotateBox', function () {
			var url = $(this).data('action');
			window.open(url);
		});		
	}
	//大图轮播
	exports.roll = function (){
		//首页 大图滚动
		var $navLi=$(".rollNav li"),
			$conLi=$(".roll ul li");
	    
	    var ind=0,
	    	timer=null,
	    	timer2=null;

	    function oAnimate(){
	    	$navLi.removeClass("active");
	        $navLi.eq(ind).addClass("active");
	        
	        $conLi.removeClass("rollshow").stop().animate({opacity:0});
	        $conLi.eq(ind).addClass("rollshow").stop().animate({opacity:1});
	    }

	    function move(){
	        ind++;
	        if(ind>=$conLi.length){
	            ind=0
	        }
	        oAnimate();
	    }

	    function otimer(){
	    	timer=setInterval(move,5000);
	    }	
	    otimer();
	    function mouseObj(obj){
	    	obj.mouseover(function (){
		        clearInterval(timer);
		        clearTimeout(timer2);
		        ind=$(this).index();
		        
		        oAnimate();
		    });
		    obj.mouseout(function (){
		        timer2=setTimeout(function (){
		            otimer();
		        },2000);
		    });
	    }

	    mouseObj($navLi);
	    mouseObj($conLi);
	}
})