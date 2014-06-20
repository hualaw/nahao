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

	    var bgColor = ['#e4e1de','#f00','#ff0'];	
	    for(var i=0;i<$conLi.length;i++){
	    	$conLi.eq(i).css("background",bgColor[i]);
	    }

	    function move(){
	        ind++;
	        if(ind>=$conLi.length){
	            ind=0
	        }
	        $navLi.removeClass("active");
	        $navLi.eq(ind).addClass("active");
	        
	        $conLi.removeClass("rollshow").stop().animate({opacity:0});
	        $conLi.eq(ind).addClass("rollshow").stop().animate({opacity:1});
	    }
	    timer=setInterval(move,2000);
	    
	    $navLi.mouseover(function (){
	        clearInterval(timer);
	        clearTimeout(timer2);
	        ind=$(this).index();
	        
	        $navLi.removeClass("active");
	        $(this).addClass("active");
	        
	        $conLi.removeClass("rollshow").stop().animate({opacity:0});
	        $conLi.eq($(this).index()).addClass("rollshow").stop().animate({opacity:1});
	    });
	    $navLi.mouseout(function (){
	        timer2=setTimeout(function (){
	            timer=setInterval(move,3000);
	        },2000);
	    });
	}
})