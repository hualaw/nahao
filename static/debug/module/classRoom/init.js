define(function(require,exports){
	var _classRoom = require("module/classRoom/classRoom");
	//做题选答案 (加背景色)
	_classRoom.options();
	//选择练习题  左右点击切换 题目选中
	_classRoom.itemClick();

	var _valid = require("module/classRoom/valid");
    // 教室-意见反馈 验证
    _valid.feedbackForm();
    // 教室-评价 验证
    _valid.evaluForm();
})