define(function(require,exports){
    require("naHaoDialog");

    //发送验证码
    exports.sendValidateCode = function (){
        $('.code').click(function() {
            var _this = $(this);
            var phone = $("input[name='phone_number']").val();
            if(!(phone)) {
                alert('请填写手机号');
                return false;
            } else if(!(/\d{11}/.test(phone))) {
                alert('请输入正确的手机号')
                return fasle;
            }
            $.ajax({
                url : 'http://www.nahaodev.com/login/send_reset_captcha',
                type : 'post',
                data : 'phone='+ phone +'&type=3',
                dataType : 'json',
                success : function (result) {
                    if(result.status == 'error') {
                        alert(result.msg);
                    }
                    //手机验证倒计时
                    require("module/common/method/countDown").countDown(_this);
                }
            }
            );
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
                    alert('验证码无效或已过期');
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
});