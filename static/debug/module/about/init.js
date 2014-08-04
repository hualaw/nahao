define(function(require,exports){	
	// 左侧导航 高亮
	require('module/common/method/curNav').curNav(".sideLeftNav","aboutContent");
    // 意见反馈 验证
	require("module/classRoom/valid").feedbackForm();
	//新加视频驱动
	if($('.videoPlay').length > 0){
    	$('.videoPlay').children().children('.container').each(function(){
			var noflash = '';
			var curfile = $(this).attr('title');
			var curimg = $(this).children('img').attr('src');
			if(!(curimg.length>0)){
				curimg = "http://img1.nahao.com/course_description_20140710182330_iVnD5YB?imageView/2/w/600";
			}
	    	/*初始化视频播放开始*/
		    var playlist = [{
			    "domain": '',
			    "file": curfile,
			    "image": curimg
			}];
		    TiZiplayer($(this).attr('id')).setup({
				playlist:playlist,
				primary:"flash",
				height:405,
				width:600
				// ,
				// adCover:"http://tizi.oss.aliyuncs.com/static/zhuangyuan/video.jpg",
				// adEnd:"http://tizi.oss.aliyuncs.com/static/zhuangyuan/video.jpg"
				// autostart:auto_start
			});
		    /*初始化视频播放结束*/
    	});
    }
})