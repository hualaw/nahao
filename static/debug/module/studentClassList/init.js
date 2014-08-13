define(function(require,exports){
	//筛选
	require("module/studentClassList/courseList").filter();
	//清空浏览记录
	require("module/studentClassList/courseList").clearHis();
})