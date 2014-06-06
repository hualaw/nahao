define(function(require,exports){
	// 发送手机验证码
	exports.sendPhoneNum = function(){
		$('.sendPhoneCode').click(function(){
			var _phoneNumber = $(this).parent().siblings().find('.phoneNum').val();
			$.ajax({
				url:'register/send_captcha',
				'type' : 'POST',
				'dataType' : 'json',
				'data' : {
                    'phone' : _phoneNumber,
                    'type' : '1'
                },
				success : function(json, status){
					if(json.status =="ok"){
						$.dialog({
							content:json.msg,
							icon:"succeed"
						})
					}
				}
			})
		})
	}
})