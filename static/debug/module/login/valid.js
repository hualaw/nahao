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
            	alert('提交成功');
            }
		});
		_Form.addRule([{
                ele: ".userName",
                datatype:"*6-8",
                nullmsg:"请输入手机号/邮箱/梯子网帐号",
                errormsg:"长度6-8个字符"
            },
            {	
               	 ele:".pwd",
               	 datatype: "*",
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
                datatype: "e",
                nullmsg: "请输入邮箱地址",
                errormsg: "请输入正确的邮箱地址"
            },
            {    
                ele: ".lname",
                datatype:"*6-8",
                nullmsg:"请输入昵称",
                errormsg:"长度6-8个字符"

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
                datatype: "*6-8",
                nullmsg: "请输入真实姓名",
                errormsg: "长度6-8个字符"

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

            },
            callback:function(data){
                alert('提交成功');
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".inputPhone",
                datatype:"*",
                nullmsg:"请输入手机号",
                errormsg:"请输入正确的手机号"
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
                alert('提交成功');
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".inputEmail",
                datatype:"e",
                nullmsg:"请输入邮箱",
                errormsg:"请输入正确的邮箱"
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
                datatype: "e",
                nullmsg: "请输入邮箱地址",
                errormsg: "请输入正确的邮箱地址"
            },
            {    
                ele: ".lname",
                datatype:"*6-8",
                nullmsg:"请输入昵称",
                errormsg:"长度6-8个字符"

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
                datatype: "*6-8",
                nullmsg: "请输入真实姓名",
                errormsg: "长度6-8个字符"

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
                ele:".setPassword",
                datatype:"*6-16",
                nullmsg:"新密码不能为空",
                errormsg:"长度6-16个字符之间"
            },
            {
                ele:".reSetPassword",
                datatype:"*6-16",
                recheck:"setPassword",
                nullmsg:"请再次输入密码",
                errormsg:"两次密码不一致！"
            }          
        ]);
    }
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
    //购买之后 选课时间 验证
    exports.enlistForm = function (){
        var _Form=$(".enlistForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                if(!$(".enlistForm li").hasClass("ctimeOn")){
                    return false;
                }
            },
            callback:function(data){
                alert('提交成功');
            }
        });
    }
    //我要开课 老师注册验证
    exports.teaRegForm = function (){
        var _Form=$(".teaRegForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                /*调用验证码验证服务端信息*/
                if(require("validForm").checkCaptcha('TeacherBox',1)){
                    // 加载MD5加密
                    require("validForm").md5(curform);
                }else{
                    return false;
                }
            },
            callback:function(data){
                alert('提交成功');
                // 异步提交
                require("validForm").reset_md5('.regTeacherForm');
                if(!data.errorcode){
                    require.async("validForm",function(ex){
                        // 提交注册结果
                        ex.changeCaptcha('TeacherBox');
                    });
                }
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele:".sEmail",
                datatype:"e",
                nullmsg:"请输入邮箱地址",
                errormsg:"请输入正确的邮箱地址"
            },
            {
                ele:".spassword",
                datatype:"*6-20",
                nullmsg:"请输入密码",
                errormsg:"长度6-20个字符之间"
            },
            {
                ele:".TeacherBoxWord",
                datatype:"/^\\w{4}$/",
                nullmsg:"请输入验证码",
                errormsg:"验证码长度是4位"
            }        
        ]);
    }
    //我要开课 试讲 信息 验证
    exports.writeInfoForm = function (){
        var _Form=$(".writeInfoForm").Validform({
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
                ele:".wUname",
                datatype:"*2-20",
                nullmsg:"请输入称呼",
                errormsg:"长度2-20个字符"
            },
            {
                ele:".loction",
                datatype:"*",
                nullmsg:"请选择地区",
                errormsg:"请选择正确的地区"
            },
            {
                ele:".radioInput",
                datatype:"*",
                nullmsg:"请选择性别",
                errormsg:"请选择正确的性别"
            },
            {
                ele:".wage",
                datatype:"*",
                nullmsg:"请输入年龄",
                errormsg:"请输入正确的年龄"
            },
            {
                ele:".checkInput",
                datatype: "*",
                nullmsg: "请选择教学阶段",
                errormsg: "您未选择教学阶段！"
            },
            {
                ele:".schoolname",
                datatype: "*",
                nullmsg: "请输入所在学校名称",
                errormsg: "请输入正确的学校名称"
            },
            {
                ele:".teaTitle",
                datatype: "*",
                nullmsg: "请选择教师职称",
                errormsg: "请选择正确的教师职称"
            },
            {
                ele:".seniority",
                datatype: "*",
                nullmsg: "请选择实际教龄",
                errormsg: "请选择正确的实际教龄"
            },
            {
                ele:".wphone",
                datatype:"m",
                nullmsg:"请输入手机号码",
                errormsg:"请输入正确的手机号码"
            },
            {
                ele:".wEmail",
                datatype:"e",
                nullmsg:"请输入邮箱地址",
                errormsg:"请输入正确的邮箱地址"
            },
            {
                ele:".wQQ",
                datatype:"n5-12 | /^\\w{0}$/",
                datatype_nonull:"n5-12",
                nullmsg:"请输入QQ号码！",
                errormsg:"长度5-12个数字"
            },
            {
                ele:".lecture",
                datatype:"*",
                nullmsg:"请选择讲课方式",
                errormsg:"请选择正确的讲课方式"
            },
            {
                ele:".lectureSub",
                datatype:"*",
                nullmsg:"请选择试讲科目",
                errormsg:"请选择正确的试讲科目"
            },
            {
                ele:".wtime",
                datatype:"*",
                nullmsg:"请输入预约时间",
                errormsg:"请输入正确的时间格式"
            },
            {
                ele:".startTime",
                datatype:"*",
                nullmsg:"请选择开始时间",
                errormsg:"请选择正确的开始时间"
            },
            {
                ele:".endTime",
                datatype:"*",
                nullmsg:"请选择结束时间",
                errormsg:"请选择正确的结束时间"
            },
            {
                ele:".subname",
                datatype:"*",
                nullmsg:"请输入课程名称",
                errormsg:"请输入正确的课程名称"
            }
            // ,
            // {
            //     ele:".subname",
            //     datatype:"*",
            //     nullmsg:"请输入课程名称",
            //     errormsg:"请输入正确的课程名称"
            // },    
        ]);
    }
})