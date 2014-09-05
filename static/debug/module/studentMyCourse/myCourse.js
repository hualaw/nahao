define(function(require,exports){
    require("naHaoDialog");
    //弹框
    var _popUp = require('module/common/method/popUp');
    // 左侧栏 高亮
    exports.leftNav = function (){
        if($(".menu li").length){
            for(var i=0;i<$(".menu li").length;i++){
                if($(".menu li").eq(i).attr("name").indexOf($("#wrapContent").attr("name"))!=-1){
                    $(".menu li").removeClass("menuOn");
                    $(".menu li").eq(i).addClass("menuOn");
                }
            }
        }
    }
    //修改头像 定位
    exports.changedHead = function (){
        $(".memberInfo .memberImg img").click(function (){
            $(".inforTab .tabh li").removeClass("inforOn");
            $(".inforTab .tabh li").eq(1).addClass("inforOn");
            $(".inforTabBox").addClass("undis");
            $(".atareditorBox").removeClass("undis");
        });
    }
    //云笔记
    exports.cNote = function (){
        $(".cListHid").on("click", '.cloudNotes', function () {
        	var shtml ='';
            var btn = $(this);
            var action = '/course/get_user_cloud_notes';
            var data = {
            		cid:btn.data('cid')
            };
            $.post(action, data, function (response) {
                if (response.status == "ok") {
                	shtml+='<h2>'+response.data.class_title+'</h2>';
                	shtml+='<div class="cnCon">';
                	shtml+='<p>'+response.data.content+'</p>';
                	shtml+='</div>';
                	$(".cnDia").html(shtml);
                	_popUp.popUp('.noteDia');
                } else if(response.status == "error"){
                	$.dialog({
                	    content:response.msg,
                	    icon:null
                	});
                }
            }, "json");
        });
        
    }
    //购买前  选开课时间
    exports.timeToggle = function (){
        $(".enlistForm .ctime").click(function (){
            if($(this).hasClass("ctimeOn")){
                $(this).removeClass("ctimeOn");
            }else{
                $(".enlistForm .ctime").removeClass("ctimeOn");
                $(this).addClass("ctimeOn");
            }
        })
    }
    //倒计时
    exports.countDown = function (obj,id,type){
        var timer = null;
        function countDown(){
            var oDate=new Date();
            if($("#"+id).val()){
            	//alert($("#"+id).val()); return false;
                array = $("#"+id).val().split(" ");
    	        FullYear = array['0'].split("-");
    	        Hours = array['1'].split(":");
                oDate.setFullYear(FullYear[0],FullYear[1],FullYear[2]);
                oDate.setHours(Hours[0],Hours[1],Hours[2]);

                var today=new Date();
                today.setFullYear(today.getFullYear(),((today.getMonth()-"")+1),today.getDate());
                today.setHours(today.getHours(),today.getMinutes(),today.getSeconds());
                var s1=parseInt(oDate.getTime());
                var s2=parseInt(today.getTime());
                var s=parseInt((s1-s2)/1000);
                var days=parseInt(s/86400);
                s%=86400;
                var hours=parseInt(s/3600);
                s%=3600;
                var mins=parseInt(s/60);
                s%=60;

                if(days<=0&&hours<=0&&mins<=0&&s<=0){
                    days=0;hours=0;mins=0;s=0;
                    clearInterval(timer);
                }

                //如果是一位的时候加前面0
                days<10?days="0"+days:days = days;
                hours<10?hours = "0"+hours:hours = hours;
                mins<10?mins = "0"+mins:mins = mins;
                s<10?s = "0"+s:s = s;

                if(type==1){
                    obj.html('<i>'+days+'</i>天'+
                            '<i>'+hours+'</i>小时'+
                            '<i>'+mins+'</i>分'+
                            '<i>'+s+'</i>秒');
                    //obj.html(days+'天 '+hours+'小时 '+mins+'分 '+s+'秒');
                }else{
                    obj.html('<strong>'+days+'</strong>天'+
                            '<strong>'+hours+'</strong>小时'+
                            '<strong>'+mins+'</strong>分'+
                            '<strong>'+s+'</strong>秒');
                }              
            }
        }
        countDown();
        timer = setInterval(countDown, 1000);  
    }
    
    //购买前--点击立即购买
    exports.soon_buy = function (){
        $("#soon_buy").click(function (){
        	
            var url = '/course/before_check_order/';
            var data = {
            	product_id: $('#product_id').val()
            };
            $.post(url, data, function (response) {
                if (response.status == "error") {
    				$.dialog({
    				    content:response.msg,
    				    icon:null,
    				    ok:function(){
    				    	window.location.href= student_url+"member/my_order/all";
    				    	return false;
    				    }
    				});
                	
                } else if(response.status == "ok"){
                	window.location.href= student_url+"pay/product/"+response.id;
                } else if(response.status == 'no_login'){
                	seajs.use('module/nahaoCommon/commonLogin',function(_c){
                		_c.cLogin();
                	});
                } else if(response.status == "nerror"){
    				$.dialog({
    				    content:response.msg,
    				    icon:null
    				});
                }
            }, "json");
        })
    }
    
    //购买前下面--点击购买课程
    exports.soon_buy_xia = function (){
        $("#soon_buy_xia").click(function (){
            var url = '/course/before_check_order/';
            var data = {
            		product_id: $('#product_id').val()
            };
            $.post(url, data, function (response) {
                if (response.status == "error") {
    				$.dialog({
    				    content:response.msg,
    				    icon:null,
      				    ok:function(){
    				    	window.location.href= student_url+"member/my_order/all";
    				    	return false;
    				    }
    				});
                } else if(response.status == "ok"){
                	window.location.href= student_url+"pay/product/"+response.id;
                }else if(response.status == 'no_login'){
                	seajs.use('module/nahaoCommon/commonLogin',function(_c){
                		_c.cLogin();
                	});
                }else if(response.status == "nerror"){
    				$.dialog({
    				    content:response.msg,
    				    icon:null 				  
    				});
                }
            }, "json");
        })
    }
    
    //我的订单列表删除
    exports.doDelMyOrder = function(){
        $(".orderComBox").on("click", '.dodel', function () {
            var btn = $(this);
            var action = '/member/action/'+btn.data('id')+'/'+btn.data('type');
            var data = {};
            $.get(action, data, function (response) {
                if (response.status == "ok") {
    				$.dialog({
    				    content:response.msg,
    				    icon:null,
                        cancel:false,
    				    ok:function(){
    				    	window.location.reload();
    				    }
    				});
                    
                } else if(response.status == "error"){
    				$.dialog({
    				    content:response.msg,
    				    icon:null
    				});
                }
            }, "json");
        });
    }
    
    //我的订单列表取消
    exports.doCancelMyOrder = function(){
        $(".orderComBox").on("click", '.docancel', function () {
            var btn = $(this);
            var action = '/member/action/'+btn.data('id')+'/'+btn.data('type');
            var data = {};
            $.get(action, data, function (response) {
                if (response.status == "ok") {
    				$.dialog({
    				    content:response.msg,
    				    icon:null,
                        cancel:false,
    				    ok:function()
    				    {
    				    	window.location.reload();
    				    }
    				});
                    
                } else if(response.status == "error"){
    				$.dialog({
    				    content:response.msg,
    				    icon:null
    				});
                }
            }, "json");
        });
    }
    
    //我的课程购买之后 列表 课程回顾 背景圆
    exports.overCourse = function (){
        for(var i=0;i< $(".outlineList li").length;i++){
            if($(".outlineList li").eq(i).find(".replay").length){
                $(".outlineList li").eq(i).find(".rCon").addClass("rConOver");
            }
        }

        //鼠标上去 显示 讲义，运笔记，评论星
//        $(".outlineList .listb").mouseover(function (){
//            $(this).find(".cListHid").show();     
//        });
//        $(".outlineList .listb").mouseout(function (){
//            $(this).find(".cListHid").hide();
//        });
        $(".evaluBtn").click(function (){
            var _this = $(this);          
           // _popUp.popUp('.evaluHtml');
        	$.dialog({
        		id:"comment_close",
                title:false,
                ok:false,
                icon:false,
                padding:0,
                content:$(".evaluHtml").html()
            });
            class_id = $(this).attr("evaluBtns");
            $("#c_class_id").val(class_id);
            exports.starClick();
            require("module/classRoom/valid").evaluForm(_this);
            
        })
    }
    
	//评论 几颗星
	exports.starClick = function (){
		$(".evalu .starBg span").click(function (){
            for(var i=0;i<$(".evalu .starBg span").length;i++){
                $(".evalu .starBg span").eq(i).removeClass("cStar");
            }
            
			var _index = $(".evalu .starBg span").index($(this));
			for(var i=0;i<_index+1;i++){
				$(".evalu .starBg span").eq(i).addClass("cStar");
			}
			$("#c_score").val(_index+1);
		});
	}
    
        //发送验证码
    exports.sendValidateCode = function (){
        $('.sendPhoneCode').click(function() {
            var _this = $(this);
            _this.attr("disabled",true);
            var phone = $("input[name='phone']").val();
            var verify_type = $("input[name='verify_type']").val();
            if(!(phone)) {
				$.dialog({
				    content:"请填写手机号",
				    icon:null
				});
                return false;
            } else if(!(/^1[3|5|7|8]\d{9}$/.test(phone))) {
				$.dialog({
				    content:"请输入正确的手机号",
				    icon:null
				});
                return fasle;
            }
            $.ajax({
                url : '/register/send_captcha',
                type : 'post',
                data : {'phone' : phone, 'type' : verify_type},
                dataType : 'json',
                success : function (result) {
                    if(result.status == 'error') {
        				$.dialog({
        				    content:result.msg,
        				    icon:null
        				});
                    } else {
                        //手机验证倒计时
                        require("module/common/method/countDown").countDown(_this);   
                    }
                }
            });
        });
    }
    
    //最新课程页面跳转
	exports.new_class_skip = function (){
		$(".newList").on("click", '.rotateBox', function () {
			var url = $(this).data('action');
			window.open(url);
		});		
	}



});