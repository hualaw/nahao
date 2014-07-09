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
            	if (data.status == "error") {
    				$.dialog({
    				    content:data.msg,
    				    icon:null
    				});
                } else if(data.status == "been_buy"){
    				$.dialog({
    				    content:data.msg,
    				    icon:null,
    				    ok:function(){
    				    	window.location.href="/member/my_order/all";
    				    }
    				});
                } else if(data.status == "ok"){
                	window.location.href="/pay/neworder/"+data.data.product_id;
                } else if(data.status =='no_login'){
                	seajs.use('module/nahaoCommon/commonLogin',function(_c){
                		_c.cLogin();
                	});
                }
            }

        });
        _Form.config({
        	showAllError:true,
            url:"/pay/add_contact",
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
        })
        // 冲掉库里面的'&nbsp:'
        _Form.tipmsg.r=" ";
        _Form.addRule([{
                ele:".inname",
                datatype:"*2-15",
                nullmsg:"请输入真实姓名",
                errormsg:"长度2-15个字符"
            },
            {
                ele:".inPhone",
                datatype:"*",
                ajaxurl:siteUrl + "register/check_phones",
                ajaxUrlName:'phone',
                nullmsg:"请输入手机号",
                errormsg:"手机号输入错误"
            } ,
            {
                ele:".inPhoneCode",
                datatype: "/^\\d{4}$/",
                nullmsg: "请输入手机验证码",
                errormsg: "手机验证码输入错误"
            }           
        ]);
    }
    
    
    //发送验证码
    exports.sendValidateCode = function (){
        $('.getVerCodea').click(function() {
            var _this = $(this);
            _this.attr("disabled",true);
            var phone = $("#phone").val();
            if(!(phone)) {
				$.dialog({
				    content:"请填写手机号",
				    icon:null
				});
                return false;
            } else if(!(/\d{11}/.test(phone))) {
				$.dialog({
				    content:"请输入正确的手机号",
				    icon:null
				});
                return fasle;
            }
            $.ajax({
                url : '/register/send_captcha/',
                type : 'post',
                data : 'phone='+ phone +'&type=2',
                dataType : 'json',
                success : function (result) {
                    if(result.status == 'error') {
        				$.dialog({
        				    content:result.msg,
        				    icon:null
        				});
                    }
                    //手机验证倒计时
                    require("module/common/method/countDown").countDown(_this);
                }
            });
        });
    }

})