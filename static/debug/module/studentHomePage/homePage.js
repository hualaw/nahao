//首页的脚本
//author shanghongliang
//date 2014-08-15 10:39
define(function(require,exports){
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
        //异步请求验证邮箱
//                $.ajax({
//                    url:'/registereck_email',
//                    type:'post',
//                    data:'email='+$.trim($('#email').val()),
//                    success:function(msg)
//                    {
//                        if(msg.status=='error')
//                        {
//                            $('#span_warning').css('color','red').show().html(msg.msg);
//                            return false;
//                        }
//                        else
//                        {
//                            $('#span_warning').hide().html('');
//                        }
//                    }
//                })

        //异步验证电话
//                if($.trim($('#phone').val())!='')
//                {
//                    $.ajax({
//                        url:'/registereck_phone',
//                        type:'post',
//                        data:'phone='+$.trim($('#phone').val()),
//                        success:function(msg)
//                        {
//                            if(msg.status=='error')
//                            {
//                                $('#span_warning').css('color','red').show().html(msg.msg);
//                                return false;
//                            }
//                            else
//                            {
//                                $('#span_warning').hide().html('');
//                            }
//                        }
//                    })
//                }


        $(function(){
            $('#cap_img').load('/index/captcha?s='+Math.random());
        })

        $('.changeOne').click(function(){
            $('#cap_img').load('/index/captcha?s='+Math.random());
        })
        $('#register_button').click(function(){
            var user_cap= $.trim($('#code').val());
            user_cap=user_cap.toLocaleLowerCase();
            $.ajax({
                url:'/index/get_captcha',
                success:function(msg)
                {
                    if(user_cap != $.trim(msg))
                    {
                        alert('验证码不对');
                        return false;
                    }
                }
            })
            $.ajax({
                url:'/register/submit',
                data:'email='+$('#email').val()+'&ephone='+$('#phone').val()+'&password='+$('#password').val(),
                type:'post',
                success:function(msg)
                {
                    if(msg.status=='error')
                    {
                        $('#span_warning').css('color','red').show().html(msg.msg);
                        return false;
                    }
                    if(msg.status=='ok')
                    {
                        $('#span_warning').hide().html('');
                        location.reload();
                    }
                }
            })
        });
    }
});