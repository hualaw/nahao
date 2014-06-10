define(function(require,exports){
	var _classRoom = require("module/classRoom/classRoom");

	var _valid = require("module/classRoom/valid");
    // 教室-意见反馈 验证
    _valid.feedbackForm();
    // 教室-评价 验证
    _valid.evaluForm();

    //滚动条
    var _scroll = require("module/common/method/scroll");

    //教室弹层
    var _popUp = require('module/common/method/popUp');
    //做题块
    $(".doWorkBoxBtn").click(function (){
        _popUp.popUp('.doWorkBoxHtml');
        var html='';
        var url = '/classroom/get_exercise/';
        var data = {
        		class_id: 4
        };
        $.post(url, data, function (response) {
            if (response.status == "error") {
            	alert(response.msg);
            } else if(response.status == "ok"){
            	//response.data
            	$.each(response.data, function(key, val) {

                	html+=	'<div>'+val.question+'</div>';
                	html+=	'<ul class="answerList">';
            		$.each(val.options, function(k, v) {

                    	html+=		'<li class="cf  ">';
                    	html+=			'<em class="fl ansIco"></em>';
                    	html+=			'<span class="options fl">'+k+'</span>';
                    	html+=			'<p class="fl">'+v+'</p>';
                    	html+=		'</li>';
            		});
                 	html+=	'</ul>';
            	});
            	html+='<p class="overBtn">';
            	html+='<a href="javascript:void(0)" class="cf btn3 btn subAns">';
            	html+='<span class="fl">提交答案</span>';
            	html+='<span class="fr"></span>';
            	html+='</a>';
            	html+='</p>';
            	$('.doWorkBox').after().html(html);
   
 
            }
        }, "json");
    });
    //得分-题目选中
    $(".scoreBoxBtn").click(function (){
        _popUp.popUp('.scoreBoxHtml');
        //选择题目 切换内容
        _classRoom.curItem();
        //得分-题目选中---左侧题目 滚动条
        //_scroll.myscroll($(".sbar"),$(".sconl"),$(".sconParl"));
        //右侧 题目 内容 滚动条
        //_scroll.myscroll($(".cbar"),$(".scoreBoxList"),$(".scoreBoxPar"));
    });
    //得分-结果选中
    $(".scorePageBtn").click(function (){
        _popUp.popUp('.scorePageHtml');
    });
    //答案统计
    $(".ansCountBtn").click(function (){
        _popUp.popUp('.ansCountHtml');
    });
    //选择练习题
    $(".exerciseBtn").click(function (){
        _popUp.popUp('.exerciseHtml');
        //做题选答案 (加背景色)
        _classRoom.options();
        //选择练习题  左右点击切换 题目选中
        _classRoom.itemClick();
    });
    //意见反馈
    $(".feedbackBtn").click(function (){
        _popUp.popUp('.feedbackHtml');
    });
    //评价
    $(".evaluBtn").click(function (){
        _popUp.popUp('.evaluHtml');
        //评论 几颗星
        _classRoom.starClick();
    });
})