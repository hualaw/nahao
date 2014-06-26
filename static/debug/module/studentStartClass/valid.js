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
    //验证试讲时间
    function check_time_pick(){
    	$('.timeSecelt').eq(1).find('li a').click(function(){
    		var start = parseInt($('.startTime').val());
        	var end = parseInt($('.endTime').val());
    		if(start>=end){
        		$('.timeSecelt').eq(1).children('.Validform_checktip').removeClass('Validform_right').addClass('Validform_wrong').html('开始时间不能晚于结束时间');
        	}
    	});
    }
    //我要开课 试讲 信息 验证
    exports.writeInfoForm = function (){
        var _Form=$(".writeInfoForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
//            ajaxPost:true,
            beforeSubmit: function(curform) {
            	if($('.timeSecelt').eq(1).children('.Validform_checktip').hasClass('.Validform_wrong')){
            		
            	}
            },
//            callback:function(data){
//                alert('提交成功');
//            },
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
                datatype:"*2-15",
                nullmsg:"请输入称呼",
                errormsg:"长度2-15个字符"
            },
//            {
//                ele:".loction",
//                datatype:"*",
//                nullmsg:"请选择地区",
//                errormsg:"请选择正确的地区"
//            },
            {
                ele:".radioInput",
                datatype:"*",
                nullmsg:"请选择性别",
                errormsg:"请选择正确的性别"
            },
            {
                ele:".wage",
                datatype:"/^\\d{2}$/",
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
                ele:".area_county_id",
                datatype: "*",
                nullmsg: "请选择所在区域",
                errormsg: "请选择所在区域"
            },
            {
                ele:"#school_type",
                datatype: "*",
                nullmsg: "请选择所在学校类型",
                errormsg: "请选择所在学校类型"
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
				datatype:"/^\\d{5,12}$/",
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
                nullmsg:"请选择开始时间"
            },
            {
                ele:".endTime",
                datatype:"*",
                nullmsg:"请选择结束时间"
            },
            {
                ele:".subname",
                datatype:"*",
                nullmsg:"请输入课程名称",
                errormsg:"请输入正确的课程名称"
            }   
        ]);
        check_time_pick();
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
})