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
    // 退课 申请状态 验证
	exports.applyFrom = function(){
		var _Form=$(".applyFrom").Validform({
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
                ele: ".courseName",
                datatype:"*",
                nullmsg:"课程名称不能为空",
                errormsg:"请输入正确的课程名称"
            },
            {	
               	 ele:".Uname",
               	 datatype: "*2-15",
     		  	 nullmsg: "请输入姓名",
       			 errormsg: "长度2-15个字符"

            },
            {   
                 ele:".contact",
                 datatype: "*",
                 nullmsg: "请输入联系方式",
                 errormsg: "请输入正确的联系方式"

            },
            {   
                 ele:".reason",
                 datatype: "*",
                 nullmsg: "退课理由不能为空",
                 errormsg: ""

            },
            {   
                 ele:".banks",
                 datatype: "*",
                 nullmsg: "请选择银行",
                 errormsg: ""

            },
            {   
                 ele:".bankCode",
                 datatype: "*",
                 nullmsg: "请输入银行卡账号",
                 errormsg: "请输入正确的银行卡账号"

            },
            {   
                 ele:".IDnum",
                 datatype: "*",
                 nullmsg: "请输入身份证号",
                 errormsg: "请输入正确的身份证号"

            }
            
        ]);
    };

    //选择和取消 关注
    function checkAttent(obj){        
        $(obj+" .attent .btn").click(function (){
            if($(this).hasClass("attentd")){
                $(this).removeClass("attentd");
            }else{
                $(this).addClass("attentd");
            }
            va.call(this);
            //验证 最多关注
            $(obj+" .attent .btn").focus(function (){
                va.call(this);
            })
            //验证 最多关注
            $(obj+" .attent .btn").blur(function (){
                va.call(this);
            })

            function va(){
                if($(obj+" .attentd").length>3){
                    $(this).parent().find(".Validform_checktip").show().html("最多只能选三科").addClass("Validform_wrong").removeClass("Validform_right");
                }else{
                    $(this).parent().find(".Validform_checktip").show().html("").addClass("Validform_right").removeClass("Validform_wrong");
                }
            }
        });
    }
    // 个人资料 （手机版） 验证
    exports.phoneForm = function(){
        //选择和取消 关注
        checkAttent(".phoneForm");

        var _Form=$(".phoneForm").Validform({
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
                ele: ".pname",
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
                ele:".pEmail",
                datatype: "e6-30",
                nullmsg: "请输入邮箱地址",
                errormsg: "请输入正确的邮箱地址"

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
                ele:".pSchool",
                ignore:"ignore",
                datatype: "*",
                nullmsg: "请输入就读学校名称",
                errormsg: "学校名称有误"

            }           
        ]);
    };
    // 个人资料 （邮箱版） 验证
    exports.emailForm = function(){
        //选择和取消 关注
        checkAttent(".emailForm")
        var _Form=$(".emailForm").Validform({
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
                ele: ".pname",
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
                ele:".pEmail",
                datatype: "e6-30",
                nullmsg: "请输入邮箱地址",
                errormsg: "请输入正确的邮箱地址"

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
                ele:".pSchool",
                ignore:"ignore",
                datatype: "*",
                nullmsg: "请输入就读学校名称",
                errormsg: "学校名称有误"

            }           
        ]);
    };
    //基本资料 修改密码验证
    exports.ichangePWForm = function (){
        var _Form=$(".ichangePWForm").Validform({
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
                recheck:"setPassword",
                nullmsg:"请再次输入密码",
                errormsg:"两次密码不一致！"
            }          
        ]);
    }
})