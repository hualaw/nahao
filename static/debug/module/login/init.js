define(function(require,exports){
	require("module/login/tab").tab();
	// 加载登陆验证
	var _valid = require('module/login/valid');
	_valid.loginForm();

	//判断当前页面时注册成功的关于我的页面
	if($('.regSuccessBox').length > 0){
		// 美化select
		$('select').jqTransSelect();
		// 美化radio
		$('input[type=radio]').jqTransRadio();
	}
        
        var _resetPwd = require("module/login/resetPwd");
	//}
	// 登陆之后验证
	_valid.loginAfterForm();
    //手机找回密码验证
//	_valid.phoneFindPW();
    //手机找回密码验证
	_valid.EmailFindPW();
    // 注册成功之后验证
	_valid.regAfterForm();
    //设置新密码验证
	_valid.setPWForm();
	//填写联系方式 验证
	_valid.inforCheckForm();
    //购买之后 选课时间 验证
	_valid.enlistForm();
	//我要开课 老师注册验证
	_valid.teaRegForm();
	//我要开课 试讲 信息 验证
	_valid.writeInfoForm();
        
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
    
})