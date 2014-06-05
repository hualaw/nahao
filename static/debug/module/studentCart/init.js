define(function(require,exports){
	require("module/studentCart/tab").tab();
	
	
	var _studentCar = require("module/studentCart/payment");
	
	
	if ($(".toPay").length>0) {
		 //require("module/cart/popUp").popUp(".popBox2");
		//点击支付按钮，表单提交
		_studentCar.pay_click_submit();
	};
	
	
	
	
	
	
	
})