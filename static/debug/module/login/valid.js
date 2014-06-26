define(function(require,exports){
    require("naHaoDialog");
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
    function checkAttent(obj){        
        $(obj+" .attent .btn").click(function (){
            if($(obj+" .attentd").length < 3){//限制只能选3个学科
                if($(this).hasClass("attentd")){
                    $(this).removeClass("attentd");
                    _record_interested_subjects($("#selected_subjects"), $(this), 'remove');
                }else{
                    $(this).addClass("attentd");
                    _record_interested_subjects($("#selected_subjects"), $(this), 'add');
                }
            } else {
                if($(this).hasClass("attentd")){
                    $(this).removeClass("attentd");
                    _record_interested_subjects($("#selected_subjects"), $(this), 'remove');
                }
                va.call(this);
            }
            
            //验证 最多关注
            $(obj+" .attent .btn").focus(function (){
                va.call(this);
            })
            //验证 最多关注
            $(obj+" .attent .btn").blur(function (){
                va_blur.call(this);
            })

            function va(){
                if($(obj+" .attentd").length>=3){
                    $(this).parent().find(".Validform_checktip").show().html("最多只能选三科").addClass("Validform_wrong").removeClass("Validform_right");
                }else{
                    $(this).parent().find(".Validform_checktip").show().html("").addClass("Validform_right").removeClass("Validform_wrong");
                }
            }
                       
            function va_blur() {
                if($(obj+" .attentd").length<=3){
                    $(this).parent().find(".Validform_checktip").show().html("").addClass("Validform_right").removeClass("Validform_wrong");
                }else{
                    $(this).parent().find(".Validform_checktip").show().html("最多只能选三科").addClass("Validform_wrong").removeClass("Validform_right");
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
                require("cryptoJs");
                var hash = CryptoJS.SHA1($(".pwd").val());
                $(".pwd").val(hash.toString())

			},
            callback:function(data){
                if(data.status == 'ok'){
                	if(data.data.redirect_url == 'reload'){
                		window.location.reload();
                	} else{
                        // 登陆成功后跳转到跳转页
                        window.location=data.data.redirect_url;
                	}

                }else{
                	console.log(data);
                    $.dialog({
                        content:data.msg
                    });
                }
                return false;
            }
		});
		_Form.addRule([{
                ele: ".userName",
                datatype:"*",
                nullmsg:"请输入手机号/邮箱",
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
        checkAttent(".loginAfterForm");
        var _Form=$(".loginAfterForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {

            },
            callback:function(data){
                $.dialog({
                    content:data.info,
                    icon:null,
                    ok:function() {
                        if(data.status == 'ok') {
                            window.location = data.url;
                        }
                    }
                });
            },
            usePlugin:{
                jqtransform:{
                    //会在当前表单下查找这些元素;
                    selector:".beauty_select,:checkbox,:radio,.decorate"    
                }
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele:".lEmail",
                datatype: "e",
                nullmsg: "请输入邮箱地址",
                errormsg: "请输入正确格式的邮箱",
                ajaxurl:'/member/check_email_availability',
                ajaxUrlName:'email'
            },
            {    
                ele: ".lname",
                datatype:"*2-15",
                nullmsg:"请输入昵称",
                errormsg:"长度2-15个字符",
                ajaxurl:"/login/check_unique_nickname",
                ajaxUrlName:'nickname'

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
                datatype: "*",
                nullmsg: "请输入真实姓名",
                errormsg: "长度4-25个字符",
                ajaxurl:'/login/check_realname_length',
                ajaxUrlName:'realname'

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
        // ajaxurl提交成功处理
        _Form.config({
            url : '/register/submit_personal_info',
            ajaxurl:{
                success:function(json,obj){
                    console.log(json);
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
                $.dialog({
                    content:data.info,
                    icon:null
                });
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
                datatype:"e",
                nullmsg:"请输入邮箱",
                errormsg:"邮箱格式不正确",
                ajaxurl:'/login/check_user_email',
                ajaxUrlName:'email'
            }        
        ]);
        // ajaxurl提交成功处理
        _Form.config({
            ajaxurl:{
                success:function(json,obj){
                    console.log(json);
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
    }
    // 注册成功之后验证
    exports.regAfterForm = function(){
        checkAttent(".regAfterForm");
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
                datatype: "e",
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
    
       /**
     * 记录学生已选感兴趣学科的学科ID
     * @param obj  container_obj 盛放subject_id的对象
     * @param obj subject_obj 当前所选学科
     * @param str operation  add 增加 remove删除
     * @returns void
     */
    function _record_interested_subjects(container_obj, subject_obj, operation) {
        var selected_subjects = container_obj.val();
        var subject_id = subject_obj.attr('subject_id');
        if(operation == 'add') {
            if(!selected_subjects) {
                selected_subjects += subject_id;
            } else {
                selected_subjects = selected_subjects + '-' + subject_id;
            }
            container_obj.val(selected_subjects);           
        } else if(operation == 'remove') {
            var index = selected_subjects.indexOf(subject_id);
            var opIndex = selected_subjects.indexOf('-');
            if(index == 0 && opIndex > 0) {
                selected_subjects = selected_subjects.replace(subject_id + '-', '');//已经选择一个以上学科,并要去掉第一个被选择的学科的情况
            }else if(index == 0 && opIndex == -1){
                selected_subjects = selected_subjects.replace(subject_id, '');//只选一个学科,去掉该学科的情况
            } else {
                selected_subjects = selected_subjects.replace('-' + subject_id, '');
            }
            container_obj.val(selected_subjects);
        }
    }
})
