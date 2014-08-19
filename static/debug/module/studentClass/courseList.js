define(function(require,exports){
	// 筛选
    exports.filter = function(){
        $('.filterList a').live('click',function(){
            $(this).addClass('active').parent().siblings().find('a').removeClass('active');
            if($('.qualityEdu').hasClass('active')){
                $('.studyGrade,.studySection').hide();
            }else{
                $('.studyGrade,.studySection').show();
            }
        });
    }
    // 清空浏览记录
    exports.clearHis = function(){
        $('.clearCount').live('click',function(){
            $('.historyList').remove();
            $('.historyNone').show();
            exports.delCookies();
        });
    }
    
    exports.delCookies = function(){
    	  document.cookie = 'recent_view' + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
    }
})