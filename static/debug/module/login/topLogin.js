define(function(require,exports){
    require("naHaoDialog");
    exports.topLogin=function(){
         // 光标进入或者离开输入框验证
        $('.topLoginForm input').focusin(function(){
            $(this).removeClass('Validform_error');
            if($(this).val() == ''){
                //console.log($(this).siblings('.ValidformInfo'));
                $(this).siblings('.ValidformInfo').addClass('ValidformInfoBg').show().find('.Validform_checktip').html($(this).siblings('.normalText').html());
            };
            // 新增判断
            if($(this).siblings('.normalText').html() == ''){
                $(this).siblings('.ValidformInfo').removeClass('ValidformInfoBg').hide();
            }
        }).focusout(function(){
            if($(this).val() !== ''){
                $(this).siblings('.ValidformInfo').addClass('ValidformInfoBg');
            }else{
                $(this).siblings('.ValidformInfo').removeClass('ValidformInfoBg').hide();
            };
        });

        var _Form=$(".topLoginForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:function(msg,o,cssctl){
                //o.obj.addClass("Validform_error");
                if (!o.obj.is("form")) {
                    var objtip = o.obj.siblings().find(".Validform_checktip");
                    objtip.text(msg);
                    o.obj.siblings().find(".Validform_checktip").show();
                    var objtip = o.obj.siblings().find(".Validform_checktip");
                    objtip.text(msg);
                    var infoObj = o.obj.siblings(".ValidformInfo");
                    // 判断验证成功
                    if (o.type == 2) {
                        infoObj.removeClass('ValidformInfoBg').hide();
                    };
                    // 如果输入错误
                    if (o.type == 3) {
                        infoObj.addClass('ValidformInfoBg').show();
                        o.obj.siblings().find(".Validform_checktip").show();
                    }
                }
            },
            showAllError:false,
            ajaxPost:true,
            beforeSubmit:function(curform){
                require("cryptoJs");
                var _email=curform.find("#top_username"),_pwd=curform.find("#top_password");
                if(_email.val()==""){
                    //_email.addClass("Validform_error");
                    return false;
                }
                if(_pwd.val()==""){
                     //_pwd.addClass("Validform_error");
                    return false;
                }
                var hash = CryptoJS.SHA1(_pwd.val());
                curform.find(".epass").val(hash.toString());
            },
            callback:function(data){
                if(data.status=='ok'){
                    location.reload();
                }else{
                    $.dialog({
                        content:data.msg
                    });
                }
            }
        });
        _Form.addRule([{
                 ele:"#top_username",
                 ignore:"ignore",
                 datatype: "m|e",
                 nullmsg: "请输入邮箱",
                 errormsg: "请输入正确的手机号/邮箱"
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