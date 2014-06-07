define(function(require,exports){
	// 请求验证库
    require("validForm");
    // 定义公共tipType;
    var commonTipType = function(msg,o,cssctl){
        if(!o.obj.is("form")){
            if(o.obj.attr('validName') =='text' || o.obj.attr('validName') =='textarea' || o.obj.attr('validName') =='captcha'){
                var objtip=o.obj.siblings(".Validform_checktip");
            }else if(o.obj.attr('validName') =='radio'){
                var objtip=o.obj.parent().parent().next(".Validform_checktip");
            }else if(o.obj.attr('validName') =='select' || o.obj.attr('validName') =='checkbox'){
                var objtip=o.obj.parent().parent().parent().next(".Validform_checktip");
            };
            cssctl(objtip,o.type);
            objtip.text(msg);
        }
    };
    //填写联系方式 验证
    exports.inforCheckForm = function (){
        var _Form=$(".inforCheckForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {

            },
            callback:function(data){
                alert('提交成功');
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele:".inname",
                datatype:"*2-20",
                nullmsg:"请输入真实姓名",
                errormsg:"长度2-20个字符"
            },
            {
                ele:".inPhone",
                datatype:"*",
                nullmsg:"请输入手机号",
                errormsg:""
            } ,
            {
                ele:".inPhoneCode",
                datatype:"*",
                nullmsg:"请输入手机验证码",
                errormsg:"请输入正确的验证码"
            }           
        ]);
    }
})