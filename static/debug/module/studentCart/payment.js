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
                    if (response.status == "error") {
        				$.dialog({
        				    content:response.msg,
        				    icon:null
        				});
                    	$.dialog.list['payFalse'].close();
                    } else if(response.status == "ok"){
        				$.dialog({
        				    content:response.msg,
        				    icon:null
        				});
        				window.location.href="/member/my_order/all";
                    } 
                }, "json");
        	});
        });
    }

})