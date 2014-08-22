//首页的脚本
//author shanghongliang
//date 2014-08-15 10:39
define(function(require,exports){
    // 请求验证库
    require("validForm");
    //dialog
    require("naHaoDialog");
    //加密
    require("cryptoJs");
    //首页初始化函数
    exports.init=function(){
        //初始化幻灯切换
        exports.bannerInit();
        //初始化首页的标签页
//        require("module/common/method/tab_nav").init();
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
    //首页右侧快速注册验证
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

         // 光标进入或者离开输入框验证
        $('.loginForm input').focusin(function(){
            $(this).removeClass('Validform_error');
            if($(this).val() == ''){
                $(this).siblings('.ValidformInfo').removeClass('ValidformInfoBg ValidformTipBg').addClass('ValidformTipBg').show().find('.Validform_checktip').html($(this).siblings('.normalText').html());
            };
            // 新增判断
            if($(this).siblings('.normalText').html() == ''){
                $(this).siblings('.ValidformInfo').removeClass('ValidformInfoBg ValidformTipBg').hide();
            }
        }).focusout(function(){
            if( $(this).siblings('.ValidformInfo').hasClass("ValidformInfoBg")){
                $(this).addClass('Validform_error');
            }
            if($(this).val() !== ''){
                $(this).siblings('.ValidformInfo').addClass('ValidformTipBg');
            }else{
                $(this).siblings('.ValidformInfo').removeClass('ValidformTipBg').hide();
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
                        infoObj.removeClass('ValidformInfoBg ValidformTipBg').hide();
                    };
                    // 如果输入错误
                    if (o.type == 3) {
                        infoObj.removeClass('ValidformInfoBg ValidformTipBg').addClass('ValidformInfoBg').show();
                        o.obj.siblings().find(".Validform_checktip").show();
                    }
                }
            },
            ajaxPost: true,
            showAllError: false,
            beforeSubmit:function(curform){
                if(curform.find('.email').val() == ''){
                    curform.find('.email').focus().next('.ValidformInfo').addClass('ValidformInfoBg').show().find('.Validform_checktip').html($(this).siblings('.normalText').html());
                    return false;
                };
                if(curform.find('.password').val() == ''){
                    curform.find('.password').focus().next('.ValidformInfo').addClass('ValidformInfoBg').show().find('.Validform_checktip').html($(this).siblings('.normalText').html());
                    return false;
                };
                if(curform.find('.authCode').val() == ''){
                    curform.find('.authCode').focus().next('.ValidformInfo').addClass('ValidformInfoBg').show().find('.Validform_checktip').html($(this).siblings('.normalText').html());
                    return false;
                };
                var hash = CryptoJS.SHA1(curform.find('.password').val());
                curform.find(".epass").val(hash.toString());
            },
            callback:function(data){
                if(data.status.toLowerCase()=='error'){
                    $.dialog({
                        content:data.msg
                    });
                    return false;
                }
                if(data.status=='ok'){
                    location.reload();
                }
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
            },
            {
                ele:".phone",
                ignore:"ignore",
                datatype: "*6-20",
                nullmsg: "请输入手机号",
                errormsg: "手机号输入错误"
            },
            {
                ele:".authCode",
                ignore:"ignore",
                datatype: "/^\\w{4}$/",
                nullmsg: "请输入验证码",
                errormsg: "验证码长度是4位"
            }
        ]);
    };
    //倒计时
    exports.countDown=function(){
        var _timeObj={},_timeInterval=[];
        $(".countDown").each(function(){
            var _this=$(this),_id=_this.attr("time_id"),_time=parseInt(_this.attr("time"))*1000;
            _timeInterval[_id]=setInterval(function(){
                var _tDay=new Date().getTime(),_dv=_time-_tDay;
                if(_dv<=0){
                    clearInterval(_timeInterval[_id]);
                    return;
                }
                var _dd=Math.floor(_dv/86400000),_dh=Math.floor((_dv%86400000)/(60*60*1000)),
                _dm=Math.floor((_dv%84600000)%3600000/60000),_ds=Math.floor((((_dv%84600000)%3600000)%6000)/1000);
                _dd="0"+_dd,_dh=(_dh<9)?("0"+_dh):_dh,_dm=(_dm<9)?("0"+_dm):_dm,_ds=(_ds<9)?("0"+_ds):_ds;
                $(".countDown[time_id="+_id+"]").html("距开课还剩<strong>"+_dd+"</strong>天<strong>"+_dh+"</strong>小时<strong>"+_dm+"</strong>分<strong>"+_ds+"</strong>秒");
            },1000);
        });
    }
});