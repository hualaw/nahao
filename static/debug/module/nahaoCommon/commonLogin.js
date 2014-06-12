define(function(require,exports){
    // 请求验证库
    require("validForm");
    // 登陆验证开始
    exports.loginForm = function(){
        $('.commonLoginBtn').click(function(){
            exports.cLogin();
        });
    };
    
    //弹出登陆层
    exports.cLogin = function(){
    	// 请求dialog
        require("naHaoDialog");
        $.dialog({
            title:'用户登录',
            content:$('#commonLogin').html().replace('loginForm_beta','loginForm'),
            icon:null,
            width:348,
            ok:false
        })
        require('module/login/valid').loginForm();
    }
})