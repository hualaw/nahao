define(function(require,exports){
	// 发送手机验证码
	exports.sendPhoneNum = function(otype){
        var oswitch = true;
		$('.sendPhoneCode').click(function(){
            var _this = $(this);
			var _phoneNumber = $(this).parent().siblings().find('.phoneNum').val();
			// 判断手机号正则
			var _regMobile = /^0?1[3|4|5|8][0-9]\d{8}$/;
		    var _testResult = _regMobile.test(_phoneNumber);
		    // 如果输入的是手机号，发送验证码
		    if (_testResult) {
		    	if(oswitch){
			        $.ajax({
						url:'register/send_captcha',
						'type' : 'POST',
						'dataType' : 'json',
						'data' : {
		                    'phone' : _phoneNumber,
		                    'type' : otype
		                },
						success : function(json, status){
							if(json.status =="ok"){
								$.dialog({
									content:json.msg,
									icon:"succeed"
								})							
							}
		                    //手机验证倒计时
		                    require("module/common/method/countDown").countDown(_this);
						}
					});
					oswitch = false;
			    }
		    }else{
		    	$.dialog({
		    		content:"手机号码输入有误，请重新输入。"
		    	})
		    };
			
		})
	}
})
