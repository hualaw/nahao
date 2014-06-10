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
        var html="";
        var url = '/classroom/get_exercise/';
        var data = {
        		class_id: 4
        };
        $.post(url, data, function (response) {
            if (response.status == "data_no") {
            	alert(response.msg);
            } else if(response.status == "data_error"){
            	alert(response.msg);
            } else if(response.status == "data_ok"){
            	
            	$.each(response.data,function(i,n){
            		html +='<div>题目内容</div>';
            	})
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