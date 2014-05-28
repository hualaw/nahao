define(function(require,exports){
	require("module/login/tab").tab();
	// 加载登陆验证
	require('module/login/valid').loginForm();
	//判断当前页面时注册成功的关于我的页面
	if($('.regSuccessBox').length > 0){
		// 美化select
		$('select').jqTransSelect();
		// 美化radio
		$('input[type=radio]').jqTransRadio();
	}
})