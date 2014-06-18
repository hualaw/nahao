define(function(require,exports){	
	// 左侧导航 高亮
	require('module/common/method/curNav').curNav(".sideLeftNav","aboutContent");
    // 意见反馈 验证
	require("module/classRoom/valid").feedbackForm();
	
})