define(function(require,exports){
	//筛选
	require("module/studentClass/courseList").filter();
	//清空浏览记录
	require("module/studentClass/courseList").clearHis();
})