define(function(require,exports){

    exports.pay_click_submit = function (){
    	// 点击网银支付按钮
    	$("#click_banks").click(function(){
    		$(".onlineBank form").submit();
    	});
    	// 点击信用卡支付按钮
    	$("#click_credit").click(function(){
    		$(".creditBox form").submit();
    	});
    	// 点击支付宝支付按钮
    	$("#click_alipayLi").click(function(){
    		$(".alipayBox form").submit();
    	});
    }
    
    exports.pay_dialog = function (){
	    //支付之后，弹出弹出层
	    require("naHaoDialog")
	    $(".ortherBtn").click(function (){
        	$.dialog({
        		id:"payFalse",
                title:false,
                ok:false,
                icon:false,
                padding:0,
                content:$(".popBox").html()
                
        	});
        	//检查支付状态
        	$(".choiceBox").on("click", '.check_pay', function () {
        		var url = '/pay/check_pay/';
                var data = {
                	order_id: $("#id").val()
                };
                $.post(url, data, function (response) {
                    if (response.status == "pay_error") {
                    	alert(response.msg);
                    	$.dialog.list['payFalse'].close();
                    } else if(response.status == "pay_ok"){
                    	alert(response.msg);
                    	window.location.href="/member/my_order/all";
                    } else if(response.status == "id_error"){
                    	alert(response.msg);
                    	$.dialog.list['payFalse'].close();
                    } else if(response.status == "data_error"){
                    	alert(response.msg);
                    	$.dialog.list['payFalse'].close();
                    }
                }, "json");
        	});
        });
    }
    
	
    
    
/*    exports.add_contact = function (){
    	// 确认订单填写联系方式
    	$("#add_contact").click(function(){
    		var flag = 0;
    		var real_name = $("#real_name").val();
            var phone = $("#phone").val();
            var verify_code = $("#verify_code").val(); 
            var product_id = $("product_id").val();
            var errorMsg = '';
            if(!real_name){
                errorMsg +='- 请填写真实姓名！\n';
            }
            if(!(phone)) {
                errorMsg +='- 请填写手机号！\n';
            } else if(!(/\d{11}/.test(phone))) {
                errorMsg +='- 请输入正确的手机号！\n';
            }
            if(phone){
            	var urls = '/register/check_phone/';
                var datas = {
                	phone: $('#phone').val()
                };
                $.post(urls, datas, function (response) {
                    if (response.status == "error") {
                    	errorMsg +='- '+response.msg+'！\n';
                    	//alert(response.msg);
                    }
                }, "json"); 
            }
            if(!verify_code){
                errorMsg +='- 验证码不能为空！\n';
            }
            if (errorMsg.length > 0){
                alert(errorMsg);
                return false;                                  
             } else { 
	            var url = '/pay/add_contact/';
	            var data = {
	            	real_name: real_name,
	            	phone: real_name,
	            	verify_code:verify_code
	            };
	
	            $.post(url, data, function (response) {
	                if (response.status == "error") {
	                	alert(response.msg);
	                } else if(response.status == "ok"){
	                	window.location.href="/pay/neworder/"+response.data.product_id;
	                } else if(response.status =='verify_code_error'){
	               	 alert(response.msg);
	                }
	            }, "json");  
             }   

    	});
    }*/
    
    //发送验证码
/*    exports.sendValidateCode = function (){
        $('.noShowBtn').click(function() {
            var phone = $("#phone").val();
            var errorMsg = '';
            if(!(phone)) {
                errorMsg +='- 请填写手机号！\n';
            } else if(!(/\d{11}/.test(phone))) {
                errorMsg +='- 请输入正确的手机号！\n';
            } 
            
            if (errorMsg.length > 0){
                alert(errorMsg);
                return false;                                  
             } else{ 
            	 
                 var url = '/register/send_captcha/';
                 var data = {
                 	phone: $('#phone').val(),
                 	type:2
                 };
                 $.post(url, data, function (response) {
                     if (response.status == "error") {
                     	alert(response.msg);
                     } else if(response.status == "ok"){
                     	alert(response.msg);
                     }
                 }, "json"); 
             }

        });
    }*/
    
    //失去焦点判断手机号是否被用过
/*    exports.checkPhone = function (){
        $('#phone').blur(function() {
            var phone = $("#phone").val();
            var errorMsg = '';
            if(!(phone)) {
                errorMsg +='- 请填写手机号！\n';
            } else if(!(/\d{11}/.test(phone))) {
                errorMsg +='- 请输入正确的手机号！\n';
            } 
            
            if (errorMsg.length > 0){
                alert(errorMsg);
                return false;                                  
             } else{ 
                 var url = '/register/check_phone/';
                 var data = {
                 	phone: $('#phone').val()
                 };
                 $.post(url, data, function (response) {
                     if (response.status == "error") {
                     	alert(response.msg);
                     } else if(response.status == "ok"){
                     	alert(response.msg);
                     }
                 }, "json"); 
             }

        });
    }*/
})