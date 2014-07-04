define(function(require,exports){
    require("naHaoDialog");

    //发送验证码
    exports.sendValidateCode = function (){
        //var oswitch = true;
        $('.code').click(function() {
           // oswitch = true;
            var _this = $(this);
            var phone = $("input[name='phone_number']").val();
            if(!(phone)) {
                $.tiziDialog({
		            content:'请填写手机号',
				    icon:null
		        });
                return false;
            } else if(!(/^1[3|5|8]\d{9}$/.test(phone))) {
                $.tiziDialog({
		            content:'请输入正确的手机号',
				    icon:null
		        });
                return fasle;
            }
           // if(oswitch){
                $.ajax({
                    url : student_url + 'login/send_reset_captcha',
                    type : 'post',
                    data : {'phone' : phone, 'type' : 3},
                    dataType : 'json',
                    success : function (result) {
                        if(result.status == 'error') {
                            $.tiziDialog({
                                content:result.msg,
                                icon:null
                            });
                        } else {
                            //手机验证倒计时
                            require("module/common/method/countDown").countDown(_this); 
                           // oswitch = true;
                        }
                    }
                });
           // }
        });
    }
    
    
    exports.checkVerifyCode = function (){
        
        var phone = $("input[name='phone_number']").val();
        var code  = $("input[name='code']").val();
        $.ajax({
            url : '/register/check_captcha',
            type : 'post',
            dataType : 'json',
//            data : 'phone=' + phone + '&verify_code=' + code,
            data : {'phone' : phone, 'verify_code' : code, 'code_type' : 3},
            success : function(result) {
                if(result.effective) {
                    $('.phoneFindPW').submit();
                } else {
                    $.tiziDialog({
                        content:'验证码无效或已过期',
                        icon:null
                    });
                    return false;
                }
            }
        });
    }
    
    exports.setPwdSuccessJump = function (){
        var seconds = $('.tips span').text();
        seconds = seconds - 1;
        if(seconds == 0) {
            window.location = '/login';
        }
        $('.tips span').text(seconds);
        setTimeout(exports.setPwdSuccessJump, 1000);
    }

    //注册页面 右侧滚动
    exports.scrollUpAndDown = function (){
        var ind = 0;
        $(".rollUpDownCopy").html($(".rollUpDown").html());
        function roll(){
            ind++;
            if(ind>$(".rollUpDown li").length){
                $(".rollBox").css("top",0);
                ind = 1;
            }
            $(".rollBox").animate({top:-ind*$(".rollUpDown li").eq(0).outerHeight(true)},300);
        }

        roll();
        var timer = setInterval(roll,2000);
    }
});