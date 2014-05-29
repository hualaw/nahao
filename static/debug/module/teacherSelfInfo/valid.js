define(function(require,exports){
	// 请求验证库
    require("validForm");
	/*教师后台管理-个人资料表单验证开始*/
	exports.teaInfoValid = function(){
		var _Form=$(".teaInfoForm").Validform({
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
    /*教师后台管理-个人资料表单验证结束*/
    /*教师后台管理-修改密码表单验证开始*/
    exports.teaPassValid = function(){
        var _Form=$(".teaPassForm").Validform({
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
    /*教师后台管理-个人资料表单验证结束*/
    /*教师后台管理-我要开课表单验证开始*/
    exports.teaClassValid = function(){
        var _Form=$(".teaClassForm").Validform({
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
    /*教师后台管理-我要开课表单验证结束*/

})