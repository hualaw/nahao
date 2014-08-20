define(function(require,exports){
    exports.topLogin=function(){
        var _Form=$(".topLoginForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:function(msg,o,cssctl){
                o.obj.addClass("Validform_error");
            },
            showAllError:false,
            ajaxPost:true,
            beforeSubmit:function(curform){
                require("cryptoJs");
                
                var _email=curform.find("#top_username"),_pwd=curform.find("#top_password");
                var hash = CryptoJS.SHA1(_pwd.val());
                _pwd.val(hash.toString());
                if(_email.val()==""){
                    _email.addClass("Validform_error");
                    return false;
                }
                if(_pwd.val()==""){
                     _pwd.addClass("Validform_error");
                    return false;
                }

            },
            callback:function(msg){
                if(msg.status=='ok'){
                    location.reload();
                }else{
                    alert('帐号或者密码错误');
                }
            }
        });

        _Form.addRule([{
                 ele:"#top_username",
                 ignore:"ignore",
                 datatype: "e",
                 nullmsg: "请输入邮箱",
                 errormsg: "请输入正确的邮箱地址"
            },
            {   
                 ele:"#top_password",
                 ignore:"ignore",
                 datatype: "*6-20",
                 nullmsg: "请输入密码",
                 errormsg: "密码长度只能在6-20位字符之间"
            }
        ]);
    }
})