define(function(require,exports){
	require("module/studentCart/tab").tab();
	
	
	var _studentCar = require("module/studentCart/payment");
	
	
	if ($(".toPay").length>0) {
		//点击支付按钮，表单提交
		_studentCar.pay_click_submit();
	};
	
	if($(".innerBox").length>0){
		//检查手机号是否被用过
		//_studentCar.checkPhone();
		//发送验证码
		//_studentCar.sendValidateCode();
		// 确认订单填写联系方式
		//_studentCar.add_contact();
	};
	
	//支付弹出层
	_studentCar.pay_dialog();
	
    //填写联系方式 验证
    require("module/studentCart/valid").inforCheckForm();
    // 发送手机验证码
    require("module/studentCart/valid").sendValidateCode();

})