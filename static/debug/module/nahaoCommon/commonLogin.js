define(function(require,exports){
    // 请求验证库
    require("validForm");
    // 登陆验证开始
    exports.loginForm = function(){
        $('.commonLoginBtn').click(function(){
            // 请求dialog
            require("naHaoDialog");
            $.dialog({
                title:'用户登录',
                content:$('#commonLogin').html().replace('loginForm_beta','loginForm'),
                icon:null,
                width:348,
                ok:false
            })
            // 验证注册表单
            var _Form=$(".loginForm").Validform({
                // 自定义tips在输入框上面显示
                tiptype:function(msg,o,cssctl){
                    var objtip=$("#msgInfo");
                    cssctl(objtip,o.type);
                    objtip.text(msg);
                },
                showAllError:false,
                ajaxPost:true,
                beforeSubmit: function(curform) {

                },
                callback:function(json){
                    if(json.status == 'ok'){
                        // 登陆成功后跳转到首页
                        window.location=siteUrl;
                    }else{
                        $.dialog({
                            content:json.msg
                        });
                    }
                    return false;
                }
            });
            _Form.addRule([{
                    ele: ".userName",
                    datatype:"*",
                    nullmsg:"请输入手机号/邮箱/梯子网帐号",
                    errormsg:"请输入正确的手机号"
                },
                {   
                     ele:".pwd",
                     datatype: "*",
                     nullmsg: "请输入密码",
                     errormsg: "密码输入错误"
                }  
            ]);
        });
    };
})