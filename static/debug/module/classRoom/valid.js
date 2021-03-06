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
    // 教室-意见反馈 验证
    exports.feedbackForm = function(){
        var _Form=$(".feedbackForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
            	
            },
            callback:function(data){
            	if(data.status == 'ok'){
    				$.dialog({
    				    content:data.msg,
    				    icon:null
    				});
                    if($(".feedback").length){
                        $.dialog.list['feedback_close'].close();
                    }
            	} else {
    				$.dialog({
    				    content:data.msg,
    				    icon:null
    				});
            	}
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".fTextarea",
                datatype: "*",
                nullmsg: "请提出您宝贵的意见或者建议",
                errormsg: ""
            },
            {   
                ele:".fname",
                datatype:"*2-20",
                nullmsg:"请输入称呼",
                errormsg:"长度2-20个字符"

            },
            {   
                ele:".fEmail",
                datatype:"e",
                nullmsg:"请输入邮箱地址",
                errormsg:"请输入正确的邮箱地址"

            }

        ]);
    };
    // 教室-评价 验证(购买后)
    exports.evaluForm = function(_this){
    	var _Form=$(".evaluForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                if(!$(".aui_content .cStar")){
                    $.dialog({
                        content:"请您为本节课打分",
                        icon:null
                    });
                    return false;
                }
            },
            callback:function(data){
            	if(data.status=="ok")
            	{
    				$.dialog({
    				    content:data.msg,
    				    icon:null
    				});
    				$.dialog.list['comment_close'].close();
    				_this.parent().append('<span class="cevaluBtn fr sevaluBtn">已评价</span>');
                    _this.remove();
            	}else if(data.status=="no_login"){
                	seajs.use('module/nahaoCommon/commonLogin',function(_c){
                		_c.cLogin();
                	})
            	} else {
    				$.dialog({
    				    content:data.msg,
    				    icon:null
    				});
            	}
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".eTextarea",
                datatype: "*",
                nullmsg: "请提出您宝贵的意见或者建议",
                errormsg: ""
            }

        ]);
    };
    
    // 教室-评价 验证（教室里面）
    exports.evaluForm_classroom = function(_this){
    	var _Form=$(".evaluForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:commonTipType,
            showAllError:false,
            ajaxPost:true,
            beforeSubmit: function(curform) {
                if(!$(".aui_content .cStar")){
                    $.dialog({
                        content:"请您为本节课打分",
                        icon:null
                    });
                    return false;
                }
            },
            callback:function(data){
            	if(data.status=="ok")
            	{
    				$.dialog({
    				    content:data.msg,
    				    icon:null
    				});
    				$.dialog.list['comment_close'].close();
            	} else {
    				$.dialog({
    				    content:data.msg,
    				    icon:null
    				});
            	}
            }
        });
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele: ".eTextarea",
                datatype: "*",
                nullmsg: "请提出您宝贵的意见或者建议",
                errormsg: ""
            }

        ]);
    };
})