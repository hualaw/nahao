define(function(require,exports){
	exports.countDown = function (_this){
        var oswitch = true;
		//验证倒计时
        if(oswitch){
            var ind=60;
            document.title = ind;
            console.log(ind)
            var timer = setInterval(function(){
                console.log(ind);
                ind--;
                if(ind<0){
                    clearInterval(timer);
                    _this.show(); 
                    _this.next('span.codeSpan').hide();
                }else{
                    _this.hide(); 
                    _this.next('span.codeSpan').show().html(ind + '秒后获取验证码');
                }
            }, 1000);
            oswitch = false;
        }
	}
})