define(function(require,exports){
	//首页初始化函数
	exports.init=function(){
		//初始化幻灯切换
		//require("module/studentHomePage/slide").init();
		//初始化首页的标签页
		require("module/studentHomePage/tab_nav").init();
		exports.bannerInit();
	}
	//初始化首页的banner
	exports.bannerInit=function(){
		var _banner=function(item,bannerList,orederList,time){
			this.item=item;
			this.time=time;
			this.bannerList=bannerList;
			this.orederList=orederList;
		}
		_banner.prototype={
			index:function(index){
				var _index=orederList.filter(".active").index();
				console(_index);
				if((!index&&index!==0)||_index==index){return;}
				var _targetOrder=this.bannerList.eq(index),_targetBanner=this.bannerList.eq(index);
				console.log(_targetOrder);
				console.log(_targetBanner);
				
			},
			next:function(){

			},
			prev:function(){

			},
			pause:function(){

			},
			start:function(){
				var _this=this;
				this.orederList.mouseover(function(){
					_this.index($(this).index());
				});
			}
		}
		new _banner($("#indexBanner"),$("#indexBanner .rollList li"),$("#indexBanner .rollNav li"),5000).start();
	}

    //验证注册   shangshikai@tizi.com
        exports.register_check=function(){
            $('#email').blur(function(){
                $('#span_warning').hide().html('');
                if($.trim($('#email').val())=='')
                {
                    $('#span_warning').css('color','red').show().html('邮箱不能为空');
                    return false;
                }
                else
                {
                    $('#span_warning').hide().html('');
                }

                $.ajax({
                    url:'/register/check_email',
                    type:'post',
                    data:'email='+$.trim($('#email').val()),
                    success:function(msg)
                    {
                        if(msg.status=='error')
                        {
                            $('#span_warning').css('color','red').show().html(msg.msg);
                            return false;
                        }
                        else
                        {
                            $('#span_warning').hide().html('');
                        }
                    }
                })
            })


            $('#password').blur(function(){
                if($.trim($('#password').val())=='')
                {
                    $('#span_warning').css('color','red').show().html('密码不能为空');
                    return false;
                }
                else
                {
                    $('#span_warning').hide().html('');
                }

                if($.trim($('#password').val()).length<6 || $.trim($('#password').val()).length>20)
                {
                    $('#span_warning').css('color','red').show().html('密码不能少于6位或大于20位');
                    return false;
                }
                else
                {
                    $('#span_warning').hide().html('');
                }
            })


            $('#phone').blur(function(){
                if($.trim($('#phone').val())!='')
                {
                    $.ajax({
                        url:'/register/check_phone',
                        type:'post',
                        data:'phone='+$.trim($('#phone').val()),
                        success:function(msg)
                        {
                            if(msg.status=='error')
                            {
                                $('#span_warning').css('color','red').show().html(msg.msg);
                                return false;
                            }
                            else
                            {
                                $('#span_warning').hide().html('');
                            }
                        }
                    })
                }
            })

            $('#register_button').click(function(){
                if($.trim($('#email').val())=='')
                {
                    $('#span_warning').css('color','red').show().html('邮箱不能为空');
                    return false;
                }
                else
                {
                    $('#span_warning').hide().html('');
                }

                $.ajax({
                    url:'/register/check_email',
                    type:'post',
                    data:'email='+$.trim($('#email').val()),
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
                        }
                    }
                })

                if($.trim($('#password').val())=='')
                {
                    $('#span_warning').css('color','red').show().html('密码不能为空');
                    return false;
                }
                else
                {
                    $('#span_warning').hide().html('');
                }

                if($.trim($('#password').val()).length<6 || $.trim($('#password').val()).length>20)
                {
                    $('#span_warning').css('color','red').show().html('密码不能少于6位或大于20位');
                    return false;
                }
                else
                {
                    $('#span_warning').hide().html('');
                }

                if($.trim($('#phone').val())!='')
                {
                    $.ajax({
                        url:'/register/check_phone',
                        type:'post',
                        data:'phone='+$.trim($('#phone').val()),
                        success:function(msg)
                        {
                            if(msg.status=='error')
                            {
                                $('#span_warning').css('color','red').show().html(msg.msg);
                                return false;
                            }
                            else
                            {
                                $('#span_warning').hide().html('');
                            }
                        }
                    })
                }

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
            })
        }
})