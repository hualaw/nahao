define(function(require,exports){
	// 公共select模拟
	require('select');
	require("module/login/tab").tab();
	// 加载登陆验证
	var _valid = require('module/login/valid');
	_valid.loginForm();

	// 选择学校组件
	require('module/common/method/setSchool').init();

	//手机号注册表单验证
	if($('.regPhoneBox').length > 0){
		_valid.regPhoneBoxForm();
	}
	//email注册表单验证
	if($('.regEmailBox').length > 0){
		_valid.regEmailBoxForm();
	}

	//判断当前页面时注册成功的关于我的页面
	if($('.regSuccessBox').length > 0){
		// 美化radio
		$('input[type=radio]').jqTransRadio();
	}
        
        var _resetPwd = require("module/login/resetPwd");
	//}
	// 登陆之后验证
	_valid.loginAfterForm();
    //手机找回密码验证
	_valid.EmailFindPW();
    // 注册成功之后验证
	_valid.regAfterForm();
    //设置新密码验证
	_valid.setPWForm();
        
    if($('.code').length > 0) {
        _resetPwd.sendValidateCode();
    }
    
    if($('#findSubmit').length > 0) {
        $('#findSubmit').click(function() {
            _resetPwd.checkVerifyCode(); 
        });
    }
    
    if($('.setSuccess').length > 0) {
        setTimeout(_resetPwd.setPwdSuccessJump, 1000);
    }
    if($("#province").length > 0) {
        require("module/studentMyCourse/area").change_area();
    }
    //注册页面 右侧滚动
    _resetPwd.scrollUpAndDown();
})