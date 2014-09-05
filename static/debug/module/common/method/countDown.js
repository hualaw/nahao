define(function(require,exports){
    var ind = 90;
    exports.countDown = function (_this){
        ind = 90;
        //验证倒计时
        var timer = setInterval(function(){
            ind--;
            if(ind<0){
                clearInterval(timer);
                _this.removeAttr("disabled").css("background","#6dcde6");
                _this.val('重新获取验证码');
            }else{
                _this.val(ind + '秒后获取验证码').css("background","#dedede");
            }
        }, 1000);
	}
})