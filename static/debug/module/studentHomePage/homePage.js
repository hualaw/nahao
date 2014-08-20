//首页的脚本
//author shanghongliang
//date 2014-08-15 10:39
define(function(require,exports){
    // 请求验证库
    require("validForm");
    //首页初始化函数
    exports.init=function(){
        //初始化幻灯切换
        exports.bannerInit();
        //初始化首页的标签页
        require("module/common/method/tab_nav").init();
        //直播客silde
        require("module/studentHomePage/slide").init($(".liveLessonWrap"),5000);
        //学员风采slide
        require("module/studentHomePage/slide").init($(".stuListCont"));
        //媒体报道slide
        require("module/studentHomePage/slide").init($(".mediaCont"));
        //首页课程列表的fixed
        exports.fiexed($(".cListWrap .chTitle"));
        //首页广告区域的fixed
        exports.fiexed($(".historyWrap .historyListWrap"));

    }
    //初始化首页的banner
    exports.bannerInit=function(){
        var _banner=function(item,bannerList,orederList,bannerSlide,ms){
            this.item=item;
            this.ms=ms;
            this.bannerList=bannerList;
            this.orederList=orederList;
            this.bannerSlide=bannerSlide;
            this.autoTimer = null;
        }
        _banner.prototype={
            index:function(index){
                var _index=this.orederList.filter(".active").index();
                if((!index&&index!==0)||_index==index){return;}
                var _targetOrder=this.orederList.eq(index),_targetBanner=this.bannerList.eq(index);
                _targetOrder.parent().children().removeClass("active");
                _targetOrder.addClass("active");
                this.bannerList.removeClass("rollshow").stop().animate({opacity:0});
                _targetBanner.addClass("rollshow").stop().animate({opacity:1});
            },
            next:function(){
                var _index=this.orederList.filter(".active").index();
                _index=_index+1;
                if(_index>=this.orederList.length){
                    _index=0;
                }
                this.index(_index);
            },
            prev:function(){
                var _index=this.orederList.filter(".active").index();
                _index=_index-1;
                if(_index<0){
                    _index=this.orederList.length-1;
                }
                this.index(_index);
            },
            start:function(){
                var _this=this;
                //下面数字标识的hover事件绑定
                this.orederList.hover(function(){
                    _this.index($(this).index());
                });
                //整个banner的hover事件绑定
                this.item.hover(function(){
                    clearInterval(_this.autoTimer);
                },function(){
                    _this.autoTimer = setInterval(function(){
                        _this.next();
                    },_this.ms);
                });
                //清空time
                clearInterval(this.autoTimer);
                //继续循环
                this.autoTimer = setInterval(function(){
                    _this.next();
                },this.ms);
                //左右轮换绑定
                this.bannerSlide.click(function(){

                    if($(this).hasClass("next")){
                        _this.next();
                    }else{
                        _this.prev();
                    }
                });

            }
        }
        new _banner($("#indexBanner"),$("#indexBanner .rollList li"),$("#indexBanner .rollNav li"),$("#indexBanner .bannerSlide"),5000).start();
    }
    //页面的fixed效果
    exports.fiexed=function(item,top){
        if(!top){
            top=parseInt($(item).parent().prop("offsetTop"));
        }
        
        $(window).scroll(function(){
           scrollFixed();
        });
        $(window).load(function(){
            scrollFixed();
        });
        $(window).resize(function(){
            scrollFixed();
        });
        function scrollFixed(){
            var _left=parseInt($(item).parent().prop("offsetLeft"));
            var _windowTop=$(window).scrollTop();
            if(_windowTop>=top){
                $(item).css({"position":"fixed","top":"0px;","left":_left+"px","z-index":"100"});
            }else{
                $(item).css({"position":"absolute","top":"0px;","left":"0px","z-index":"1"});
            }
            var scrollTop=document.body.scrollTop||document.documentElement.scrollTop;
        }
    }
    //验证注册   shangshikai@tizi.com
    exports.register_check=function(){
        //加载验证码
        $(function(){
            $('#cap_img').load('/index/captcha?s='+Math.random());
        });
        //重新加载验证码
        $('.changeOne,#cap_img').click(function(e){
            $('#cap_img').load('/index/captcha?s='+Math.random());
            e.preventDefault();
        });

        var _lastError="",_lastBlur="",_msgObj={};
        $(".loginForm input").blur(function(){
            _lastBlur=$(this).attr("id");
        });
        //input表单获取焦点
        $(".loginForm input").focus(function(){
            $(this).removeClass("Validform_error");
            $("#span_warning").text("").removeClass("Validform_checktip Validform_wrong");
            for(var val in _msgObj){
                if(val!=$(this).attr("id")&&_msgObj[val]!="通过信息验证！"){
                    if(_msgObj[_lastBlur]&&_msgObj[_lastBlur]!="通过信息验证！"){
                        $("#span_warning").text(_msgObj[_lastBlur]).addClass("Validform_checktip Validform_wrong");
                        return;
                    }
                    $("#span_warning").text(_msgObj[val]).addClass("Validform_checktip Validform_wrong");
                    return;
                }
            }
        });
        var _Form=$(".loginForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype:function(msg,o,cssctl){
                _msgObj[o.obj.attr("id")]=msg;
                if(msg!="通过信息验证！"){
                    var objtip=$("#span_warning");
                    cssctl(objtip,o.type);
                    objtip.text(msg);
                    //console.log(msg);
                    console.log( objtip.text());
                }
            },
            showAllError:false,
            ajaxPost:true,
            beforeSubmit:function(curform){
                var _email=curform.find("#email"),_pwd=curform.find("#password"),
                _phone=curform.find("#phone"),_code=curform.find("#code"),
                _msg="";
                //邮箱为空
                if(_email.val()==""){
                    _msg=_email.attr("nullmsg");
                    //_msgObj[_email.attr("id")]=_msg;
                    $("#span_warning").text(_msg).addClass("Validform_checktip Validform_wrong");
                    _email.addClass("Validform_error");
                    return false;
                }
                //密码为空
                if(_pwd.val()==""){
                    _msg=_pwd.attr("nullmsg");
                    //_msgObj[_pwd.attr("id")]=_msg;
                    $("#span_warning").text(_msg).addClass("Validform_checktip Validform_wrong");
                    _pwd.addClass("Validform_error");
                    return false;
                }
                //验证码为空
                if(_code.val()==""){
                    _msg=_code.attr("nullmsg");
                    //_msgObj[_code.attr("id")]=_msg;
                    $("#span_warning").text(_msg).addClass("Validform_checktip Validform_wrong");
                    _code.addClass("Validform_error");
                    return false;
                }
            },
            callback:function(msg){
                if(msg.status.toLowerCase()=='error'){
                    $('#span_warning').css('color','red').show().text(msg.msg);
                    return false;
                }
                if(msg.status=='ok'){
                    location.reload();
                }
            }
        });
        _Form.addRule([{
                 ele:"#email",
                 ignore:"ignore",
                 datatype: "e",
                 forceRecheck:"true",
                 noFocus:"noFocus",
                 nullmsg: "请输入邮箱",
                 errormsg: "请输入正确的邮箱地址"
            },
            {   
                 ele:"#password",
                 ignore:"ignore",
                 datatype: "*6-20",
                 forceRecheck:"true",
                 noFocus:"noFocus",
                 nullmsg: "请输入密码",
                 errormsg: "密码长度只能在6-20位字符之间"
            },
            {   
                ele: "#phone",
                datatype:"m",
                ignore:"ignore",
                forceRecheck:"true",
                noFocus:"noFocus",
                errormsg:"请输入正确的手机号码"
            },
            {   
                ele: "#code",
                datatype:"/^\\w{4}$/",
                ignore:"ignore",
                forceRecheck:"true",
                noFocus:"noFocus",
                nullmsg:"请输入验证码",
                errormsg:"验证码长度是4位"
            }
        ]);
    };
    // 首页右侧快速注册验证
    exports.register_check_new = function(){
        // 光标进入或者离开输入框验证
        $('.loginForm input').focusin(function(){
            $(this).removeClass('Validform_error');
            if($(this).val() == ''){
                $(this).siblings('.ValidformInfo').addClass('ValidformInfoBg').show().find('.Validform_checktip').html($(this).siblings('.normalText').html());
            };
        }).focusout(function(){
            if($(this).val() !== ''){
                $(this).siblings('.ValidformInfo').addClass('ValidformInfoBg');
            }else{
                $(this).siblings('.ValidformInfo').removeClass('ValidformInfoBg').hide();
            };
        });
        // 验证规则
        var _Form=$(".loginForm").Validform({
            tiptype: function (msg, o, cssctl) {
                if (!o.obj.is("form")) {
                    var objtip = o.obj.siblings().find(".Validform_checktip");
                    objtip.text(msg);
                    o.obj.siblings().find(".Validform_checktip").show();
                    var objtip = o.obj.siblings().find(".Validform_checktip");
                    objtip.text(msg);
                    var infoObj = o.obj.siblings(".ValidformInfo");
                    // 判断验证成功
                    if (o.type == 2) {
                        infoObj.removeClass('ValidformInfoBg').hide();
                    };
                    // 如果输入错误
                    if (o.type == 3) {
                        infoObj.addClass('ValidformInfoBg').show();
                        o.obj.siblings().find(".Validform_checktip").show();
                    }
                }
            },
            ajaxPost: true,
            showAllError: false,
            beforeSubmit:function(curform){
                if(curform.find('.email').val() == ''){
                    curform.find('.email').focus().next('.ValidformInfo').addClass('ValidformInfoBg').show().find('.Validform_checktip').html($(this).siblings('.normalText').html());
                    curform.find('.password').next('.ValidformInfo').hide();
                    return false;
                };
                if(curform.find('.password').val() == ''){
                    curform.find('.password').focus().next('.ValidformInfo').addClass('ValidformInfoBg').show().find('.Validform_checktip').html($(this).siblings('.normalText').html());
                    curform.find('.email').next('.ValidformInfo').hide();
                    return false;
                };
            },
            callback:function(data){
                alert(data);
                return false;
            }
        });
        _Form.addRule([{
                ele: ".email",
                ignore:'ignore',
                datatype: "e",
                nullmsg: "请输入邮箱",
                errormsg: "请输入正确的邮箱地址"
            },
            {
                ele:".password",
                ignore:"ignore",
                datatype: "*6-20",
                nullmsg: "请输入密码",
                errormsg: "密码长度只能在6-15位字符之间"
            }
        ]);
    }
});