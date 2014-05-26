define(function(require,exports){
	if ($(".popBox").length>0) {
		require("module/cart/popUp").popUp(".popBox");
	};
		//判断当前页面时注册成功的关于我的页面
	if($('.writeInfo').length > 0){
		// 美化select
		$('select').jqTransSelect();
		// 美化radio
		$('input[type=radio]').jqTransRadio();
		// 美化checkBo
		$('input[type=checkbox]').jqTransCheckBox();

		require("module/startClass/edit").edit();
	}
})