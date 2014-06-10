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
    // 手机注册验证
    exports.regPhoneBoxForm = function(){
        var _Form=$(".regPhoneBox").Validform({
            // 自定义tips在输入框上面显示
            tiptype:3,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                
            },
            callback:function(json){
                if(json.status =="ok"){
                    window.location=perfectUrl;
                }else{
                    $.dialog({
                        content:json.msg
                    })
                }
            }
        });
        _Form.addRule([{
                ele: ".phoneNum",
                datatype:"m",
                ajaxurl:siteUrl + "register/check_phone",
                ajaxUrlName:'phone',
                nullmsg:"请输入手机号",
                errormsg:"手机号输入错误"
                
            },
            {   
                 ele:".pwd",
                 datatype: "*6-20",
                 nullmsg: "请输入密码",
                 errormsg: "密码输入错误"
            },
            {   
                 ele:".codeInput",
                 datatype: "/^\\d{4}$/",
                 nullmsg: "请输入手机验证码",
                 errormsg: "长度是四位数字"
            },
            {   
                 ele:"checkbox:first",
                 datatype: "*",
                 nullmsg: "请同意服务协议",
                 errormsg: "未同意服务协议"
            }
        ]);
        // ajaxurl提交成功处理
        _Form.config({
            ajaxurl:{
                success:function(json,obj){
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
        // 发送手机验证码
        require('module/common/method/send').sendPhoneNum(1);
    };
    // 邮箱注册验证
    exports.regEmailBoxForm = function(){
        var _Form=$(".regEmailBox").Validform({
            // 自定义tips在输入框上面显示
            tiptype:3,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                
            },
            callback:function(json){
                if(json.status =="ok"){
                    window.location=perfectUrl;
                }else{
                     $.dialog({
                        content:json.msg
                    })
                }
            }
        });
        _Form.addRule([{
                ele: ".email",
                datatype:"e",
                ajaxurl:siteUrl + "register/check_email",
                ajaxUrlName:'email',
                nullmsg:"请输入邮箱地址",
                errormsg:"长度6-30个字符"
            },
            {   
                 ele:".pwd",
                 datatype: "*6-20",
                 nullmsg: "请输入密码",
                 errormsg: "密码输入错误"
            },
            {   
                ele:".ephone",
                datatype: "m",
                nullmsg: "请输入手机号码",
                errormsg: "手机号码输入错误",
                ignore:"ignore"
            },
            {   
                 ele:"radio:first",
                 datatype: "*",
                 nullmsg: "请同意服务协议",
                 errormsg: "未同意服务协议"
            }
        ]);
        // ajaxurl提交成功处理
        _Form.config({
            ajaxurl:{
                success:function(json,obj){
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
    //选择和取消 关注
    function checkAttent(){        
        $(".objBox a").click(function (){
            if($(this).hasClass("active")){
                $(this).removeClass("active");
            }else{
                $(this).addClass("active");
            }
            va.call(this);
            //验证 最多关注
            $(".objBox a").focus(function (){
                va.call(this);
            })
            //验证 最多关注
            $(".objBox a").blur(function (){
                va.call(this);
            })

            function va(){
                if($(".objBox .active").length>3){
                    $(this).parent().find(".Validform_checktip").show().html("最多只能选三科").addClass("Validform_wrong").removeClass("Validform_right");
                }else{
                    $(this).parent().find(".Validform_checktip").show().html("").addClass("Validform_right").removeClass("Validform_wrong");
                }
            }
        });
    }
	// 登陆验证开始
	exports.loginForm = function(){
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
            callback:function(data){
                if(data.status == 'ok'){
                    // 登陆成功后跳转到跳转页
                    window.location=data.data.redirect_url;
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
                errormsg:"长度2-15个字符"
            },
            {	
               	 ele:".pwd",
                 datatype: "*6-20",
                 nullmsg: "请输入密码",
                 errormsg: "密码输入错误"

            }
            
        ]);
    };
    // 登陆之后验证
    exports.loginAfterForm = function(){
        checkAttent();
        var _Form=$(".loginAfterForm").Validform({
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
        _Form.addRule([{
                ele:".lEmail",
                datatype: "e6-30",
                nullmsg: "请输入邮箱地址",
                errormsg: "长度6-30个字符"
            },
            {    
                ele: ".lname",
                datatype:"*2-15",
                nullmsg:"请输入昵称",
                errormsg:"长度2-15个字符"

            },
            {    
                ele:".loction",
                datatype: "*",
                nullmsg: "请输入选择地区",
                errormsg: "请选择正确的地区"
            },
            {    
                ele:".subjectInput",
                datatype: "*",
                nullmsg: "请选择年级",
                errormsg: "选择年级错误"

            },
            {    
                ele:".pUname",
                ignore:"ignore",
                datatype: "*2-15",
                nullmsg: "请输入真实姓名",
                errormsg: "长度2-15个字符"

            },
            {    
                ele:".sex",
                ignore:"ignore",
                datatype: "*",
                nullmsg: "请选择性别",
                errormsg: "请选择性别"

            },
            {    
                ele:".schoolInput",
                ignore:"ignore",
                datatype: "*",
                nullmsg: "请输入就读学校名称",
                errormsg: "学校名称有误"

            }  
            
        ]);
    };
    //手机找回密码验证
    exports.phoneFindPW = function (){
        var _Form=$(".phoneFindPW").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                return require("module/login/resetPwd").sendValidateCode();
            },
            callback:function(data){
                alert('提交成功');
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".inputPhone",
                datatype:"m",
                nullmsg:"请输入手机号码",
                errormsg:"请输入正确的手机号码"
            },
            {
                ele: ".inputPhoneCode",
                datatype:"/^\\d{4}$/",
                datatype_allownull:"/^\\d{4}$/ | /^\\w{0}$/",
                nullmsg:"请输入验证码",
                errormsg:"验证码长度是4位"
            }       
        ]);
    }
    //邮箱找回密码验证
    exports.EmailFindPW = function (){
        var _Form=$(".EmailFindPW").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {

            },
            callback:function(data){
                if(data.status == 1) {
                    alert(data.msg);
                }
            }
        });
        _Form.config({
            showAllError:true,
            url:"/login/send_reset_email"
        })
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".inputEmail",
                datatype:"e6-30",
                nullmsg:"请输入邮箱",
                errormsg:"长度6-30个字符",
                ajaxurl:'/login/check_user_email'
            }        
        ]);
    }
    // 注册成功之后验证
    exports.regAfterForm = function(){
        checkAttent();
        var _Form=$(".regAfterForm").Validform({
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
        _Form.addRule([{
                ele:".lEmail",
                datatype: "e6-30",
                nullmsg: "请输入邮箱地址",
                errormsg: "请输入正确的邮箱地址"
            },
            {    
                ele: ".lname",
                datatype:"*2-15",
                nullmsg:"请输入昵称",
                errormsg:"长度2-15个字符"

            },
            {    
                ele:".loction",
                datatype: "*",
                nullmsg: "请输入选择地区",
                errormsg: "请选择正确的地区"
            },
            {    
                ele:".subjectInput",
                datatype: "*",
                nullmsg: "请选择年级",
                errormsg: "选择年级错误"

            },
            {    
                ele:".pUname",
                ignore:"ignore",
                datatype: "*2-15",
                nullmsg: "请输入真实姓名",
                errormsg: "长度2-15个字符"

            },
            {    
                ele:".sex",
                ignore:"ignore",
                datatype: "*",
                nullmsg: "请选择性别",
                errormsg: "请选择性别"

            },
            {    
                ele:".schoolInput",
                ignore:"ignore",
                datatype: "*",
                nullmsg: "请输入就读学校名称",
                errormsg: "学校名称有误"

            }  
            
        ]);
    };
    //设置新密码验证
    exports.setPWForm = function (){
        var _Form=$(".setPWForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:false,
            beforeSubmit: function(curform) {

            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele:".setPassword",
                datatype:"*6-20",
                nullmsg:"新密码不能为空",
                errormsg:"长度6-16个字符之间"
            },
            {
                ele:".reSetPassword",
                datatype:"*6-20",
                recheck:"setPassword",
                nullmsg:"请再次输入密码",
                errormsg:"两次密码不一致！"
            }          
        ]);
    }
})
