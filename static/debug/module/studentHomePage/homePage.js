define(function(require,exports){
	//首页初始化函数
	exports.init=function(){
		//初始化首页的标签页
		require("module/studentHomePage/tab_nav").init();
	}
})