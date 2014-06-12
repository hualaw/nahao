define(function(require,exports){
	var _classRoom = require("module/classRoom/classRoom");

	var _valid = require("module/classRoom/valid");

    //滚动条
    var _scroll = require("module/common/method/scroll");

    //教室弹层
    var _popUp = require('module/common/method/popUp');
    //做题块
    $(".doWorkBoxBtn").click(function (){
        _popUp.popUp('.doWorkBoxHtml');
        _classRoom.show_question();
        _classRoom.doWork();
        //做题选答案 (加背景色)
        _classRoom.options();

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

        //选择练习题  左右点击切换 题目选中
        _classRoom.itemClick();
    });
    //意见反馈
    $(".feedbackBtn").click(function (){
        _popUp.popUp('.feedbackHtml');
        // 教室-意见反馈 验证
        _valid.feedbackForm();
    });
    //评价
/*    $(".evaluBtn").click(function (){
    	alert(12321);
//        _popUp.popUp('.evaluHtml');
//    	//_lens = $('.starBg ').children('.cStar').size();
//        alert(22);
//    	class_id = $(this).attr("evaluBtns");
//    	alert(class_id);
    	console.log(class_id);
    	return fasle;
        //评论 几颗星
        _classRoom.starClick();
        // 教室-评价 验证
        _valid.evaluForm();
    });*/
})