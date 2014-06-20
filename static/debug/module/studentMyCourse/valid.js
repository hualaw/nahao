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
            	if(data.status == 'ok')
            	{
    				$.dialog({
    				    content:data.msg,
    				    icon:null,
    				    ok:function(){
    				    	window.location.href="/member/my_order/all";
    				    }
    				});
            	} else if(data.status == 'error') {
    				$.dialog({
    				    content:data.msg,
    				    icon:null
    				});
            	}
            },
/*            usePlugin:{
                jqtransform:{
                    //会在当前表单下查找这些元素;
                    selector:"select,:checkbox,:radio,.decorate"    
                }
            }*/
		});
        _Form.config({
        	showAllError:true,
            url:"/member/save_refund",

        })
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
                va.call(this);
            })

            function va(){
                if($(obj+" .attentd").length>=3){
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
                alert(data.msg);
                if(data.status == 'ok') {
                    window.location.reload();
                }
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
                datatype: "e",
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
                datatype: "*2-15",
                nullmsg: "请输入真实姓名",
                ignore:"ignore",
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
                alert(data.msg);
                if(data.status == 'ok') {
                    window.location.reload();
                }
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
        _Form.addRule([{
                ele: ".pname",
                datatype:"*",
                nullmsg:"请输入昵称",
                errormsg:"长度4-25个字符",
                ajaxurl:"/member/validate_user_nickname",
                ajaxUrlName:'nickname',
            },
            {
                ele: ".phone_number",
                datatype:"m",
                ignore:"ignore",
                nullmsg:"请输入手机号",
                errormsg:"请输入正确的手机号",
                ajaxurl:'/register/check_phones',
                ajaxUrlName:'phone',
                
            },
            {    
                ele:".loction",
                datatype: "*",
                nullmsg: "请输入选择地区",
                errormsg: "请选择正确的地区"

            },
            {    
                ele:".pEmail",
                datatype: "e",
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
                datatype: "*",
                nullmsg: "请输入真实姓名",
                errormsg: "长度4-25个字符",
                ajaxurl:"/member/check_realname_length",
                ajaxUrlName:"realname"

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
                if(data.status == 'ok') {
                    alert('密码修改成功, 页面将跳转到登陆页面');
                    window.location = data.url;
                } else {
                    alert(data.info);
                }
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".iniPassword",
                datatype:"*",
                nullmsg:"请输入密码",
                errormsg:"请输入正确的密码",
                ajaxurl:'/member/front_check_password',
                ajaxUrlName:'password'
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
            url:'/member/front_modify_password',
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