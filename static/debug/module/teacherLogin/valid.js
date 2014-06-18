define(function(require,exports){
	// 请求验证库
    require("validForm");
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
                nullmsg:"请输入手机号/邮箱",
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
})