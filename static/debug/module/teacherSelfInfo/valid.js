define(function(require,exports){
    require("naHaoDialog");
    // 请求验证库
    require("validForm");
    // 请求公共验证信息
    //var sDataType = require("module/common/basics/dataType").dataType();
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
	/*教师后台管理-个人资料表单验证开始*/
	exports.teaInfoValid = function(){
        var _card = require("module/common/method/bankcard");
        var _idcard = require("module/common/method/idCard");
		var _Form=$(".teaInfoForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                cardReg();
                idcardReg();
                
                if($(".bankId").hasClass("Validform_error")){
                    $.dialog({
                        content:"请输入正确的身份证号",
                        icon:null
                    });
                    return false;
                }
                if($(".cardId").hasClass("Validform_error")){
                    $.dialog({
                        content:"请输入正确的银行卡账号",
                        icon:null
                    });
                    return false;
                }
            },
            callback:function(data){
                $.dialog({
                    content:data.msg,
                    icon:null,
                    ok: function() {
                        if(data.status == 'ok') {
                            window.location.reload();
                        }    
                    }
                })
            },
            usePlugin:{
                jqtransform:{
                    //会在当前表单下查找这些元素;
                    selector:".select_beauty,:checkbox,:radio,.decorate"
                }
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([
            {
                ele: ".userName",
                datatype:"*",
                nullmsg:"请输入昵称",
                errormsg:"长度4-16个字符",
                ajaxurl:'/selfInfo/validate_user_nickname',
                ajaxUrlName:'nickname'
            },
            {
                ele:".userRealName",
                datatype: "*",
                nullmsg: "请输入真实姓名",
                errormsg: "长度4-16个字符",
                ajaxurl: "/selfInfo/check_realname_length",
                ajaxUrlName:"realname"

            },
            {
                ele: ".level",
                datatype:"*",
                nullmsg:"请选择教学阶段",
                errormsg:"请选择教学阶段"
            },
            {
                ele:".subject",
                datatype: "*",
                nullmsg: "请选择教学科目",
                errormsg: "请选择教学科目"

            },
            {
                ele:".rank",
                datatype: "*",
                nullmsg: "请选择教师职称",
                errormsg: "请选择教师职称"

            },
            {
                ele:".sex",
                datatype: "*",
                nullmsg: "请选择性别",
                errormsg: "请选择性别"

            },
            {
                ele:".schoolAge",
                datatype: "*",
                nullmsg: "请选择教龄",
                errormsg: "请选择教龄"

            },
            {
                ele:".phone",
                datatype: "m",
                nullmsg: "请输入手机号码",
                errormsg: "请输入手机号码",
                ignore:"ignore"

            },
            {
                ele:".email",
                datatype: "e",
                nullmsg: "请输入邮箱地址",
                errormsg: "请输入正确的邮箱地址"

            },
            {
                ele:".bankType",
                datatype: "*",
                nullmsg: "请选择银行",
                errormsg: "请选择银行"
            },
            {
                ele:".bank",
                datatype: "*",
                nullmsg: "请输入银行名称",
                errormsg: "请输入银行名称"
            },
            {
                ele:".bankId",
                datatype: "*",
                nullmsg: "请输入银行卡号",
                errormsg: "请输入银行卡号"
            },
            {
                ele:".cardId",
                datatype: "*",
                nullmsg: "请输入身份证号码",
                errormsg: "请输入身份证号码"
            }
        ]);
        function cardReg(){
            if(_card.luhmCheck($(".bankId").val())==false&&$(".bankId").val()!=""){
                $(".bankId").addClass("Validform_error");
                $(".bankId").next(".Validform_checktip").addClass("Validform_wrong").removeClass("Validform_right").html("请输入正确的银行卡账号");
            }
        }
        function idcardReg(){       
            if(_idcard.idCard($(".cardId").val())==false&&$(".cardId").val()!=""){
                $(".cardId").addClass("Validform_error");
                $(".cardId").next(".Validform_checktip").addClass("Validform_wrong").removeClass("Validform_right").html("请输入正确的身份证号");
            }
        }

        $(".bankId").blur(function (){
            cardReg();
        });

        $(".cardId").blur(function (){
            idcardReg();
        });
    };
    /*教师后台管理-个人资料表单验证结束*/
    /*教师后台管理-修改密码表单验证开始*/
    exports.teaPassValid = function(){
        var _Form=$(".teaPassForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                require("cryptoJs");
                var hash = CryptoJS.SHA1($(".iniPassword").val());
                $(".iniPassword").val(hash.toString());
                var hash_set = CryptoJS.SHA1($(".setPassword").val());
                $(".setPassword").val(hash_set.toString());
                var hash_reset = CryptoJS.SHA1($(".reSetPassword").val());
                $(".reSetPassword").val(hash_reset.toString());
            },
            callback:function(data){
                if(data.status == 'ok') {
                    $.dialog({
                        content:'密码修改成功, 页面将跳转到登陆页面',
                        icon:null,
                        ok:function () {
                            window.location = data.url;
                        }
                    })
                } else {
                    $.dialog({
                        content:data.info
                    })
                }
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".iniPassword",
                datatype:"*6-20",
                nullmsg:"请输入密码",
                errormsg:"请输入正确的密码"
            },
            {
                ele:".setPassword",
                datatype:"*6-20",
                nullmsg:"新密码不能为空",
                errormsg:"长度6-20个字符之间"
            },
            {
                ele:".reSetPassword",
                datatype:"*6-20",
                recheck:"set_password",
                nullmsg:"请再次输入密码",
                errormsg:"两次密码不一致！"
            }
        ]);
        // ajaxurl提交成功处理
        _Form.config({
            url:'/selfInfo/front_modify_password',
            ajaxurl:{
                success:function(json,obj){
//                    console.log(json);
                    if(json.status == 'ok'){
                        $(obj).siblings('.Validform_checktip').html(json.msg);
                        $(obj).siblings('.Validform_checktip').removeClass('Validform_loading').addClass('Validform_right');
                        $(obj).removeClass('Validform_error');
                    }else{
                        $(obj).siblings('.Validform_checktip').html(json.msg);
                        $(obj).siblings('.Validform_checktip').removeClass('Validform_loading').addClass('Validform_wrong');
                    }
                }
            }
        });
    };
    /*教师后台管理-个人资料表单验证结束*/
    /*教师后台管理-我要开课表单验证开始*/
    exports.teaClassValid = function(){
        var _Form=$(".teaClassForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {

            },
            callback:function(data){
                alert('提交成功');
            },
            usePlugin:{
                jqtransform:{
                    //会在当前表单下查找这些元素;
                    selector:"select,:checkbox,:radio,.decorate"
                }
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([
            {
                ele:".userRealName",
                datatype: "*6-8",
                nullmsg: "请输入真实姓名",
                errormsg: "长度6-8个字符"

            },
            {
                ele:".province",
                datatype: "*",
                nullmsg: "请选择省份",
                errormsg: "请选择省份"

            },
            {
                ele:".city",
                datatype: "*",
                nullmsg: "请选择城市",
                errormsg: "请选择城市"

            },
            {
                ele:".zone",
                datatype: "*",
                nullmsg: "请选择地区",
                errormsg: "请选择地区"

            },
            {
                ele:".sex",
                datatype: "*",
                nullmsg: "请选择性别",
                errormsg: "请选择性别"

            },
            {
                ele:".age",
                datatype: "*",
                nullmsg: "请填写年龄",
                errormsg: "请填写年龄"

            },
            {
                ele: ".level",
                datatype:"*",
                nullmsg:"请选择教学阶段",
                errormsg:"请选择教学阶段"
            },
            {
                ele:".school",
                datatype: "*",
                nullmsg: "请输入所在学校名称",
                errormsg: "学校名称有误"
            },
            {
                ele:".rank",
                datatype: "*",
                nullmsg: "请选择教师职称",
                errormsg: "请选择教师职称"

            },
            {
                ele:".schoolAge",
                datatype: "*",
                nullmsg: "请选择教龄",
                errormsg: "请选择教龄"

            },
            {
                ele:".phone",
                datatype: "*",
                nullmsg: "请输入手机号码",
                errormsg: "请输入手机号码"
            },
            {
                ele:".email",
                datatype: "e",
                nullmsg: "请输入邮箱地址",
                errormsg: "请输入正确的邮箱地址"

            },
            {
                ele:".qqcode",
                datatype: "*",
                nullmsg: "请输入QQ号码",
                errormsg: "请输入QQ号码"
            },
            {
                ele:".classMethod",
                datatype: "*",
                nullmsg: "请选择讲课方式",
                errormsg: "请选择讲课方式"

            },
            {
                ele:".subject",
                datatype: "*",
                nullmsg: "请选择试讲科目",
                errormsg: "请选择试讲科目"

            },
            {
                ele:".className",
                datatype: "*",
                nullmsg: "请输入课程名称",
                errormsg: "请输入课程名称"
            }
        ]);
    };
    /*教师后台管理-我要开课表单验证结束*/

})