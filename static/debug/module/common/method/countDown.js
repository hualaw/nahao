define(function(require,exports){
    var oswitch = true;
	exports.countDown = function (_this){
        oswitch = true;
        // if(oswitch){
            
        // }
		//验证倒计时
        var ind=60;
        var timer = setInterval(function(){
            ind--;
            if(ind<0){
                clearInterval(timer);
                _this.show(); 
                _this.next('span.codeSpan').hide();
               oswitch = false;
            }else{
                _this.hide(); 
                _this.next('span.codeSpan').show().html(ind + '秒后获取验证码');
            }
        }, 1000);
	}
})