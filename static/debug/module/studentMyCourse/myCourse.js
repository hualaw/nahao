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

    //云笔记
    exports.cNote = function (){
        $(".cloudNotes").click(function (){
            _popUp.popUp('.noteDia');
        })
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
                clearInterval(timer);
                obj.html("已到时");
            }else{
                if(type==1){
                    obj.html(days+'天   '+hours+'小时   '+mins+'’'+s+'“');
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
                if (response.status == "order_exist") {
                	alert(response.msg);
                	window.location.href="/member/my_order/all";
                } else if(response.status == "order_buy"){
                	alert(response.msg);
                } else if(response.status == "ok"){
                	window.location.href="/pay/product/"+response.id;
                } else if(response.status == 'no_login'){
                	seajs.use('module/nahaoCommon/commonLogin',function(_c){
                		_c.cLogin();
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
            	product_id: $('#product_id_xia').val()
            };
            $.post(url, data, function (response) {
                if (response.status == "order_exist") {
                	alert(response.msg);
                	window.location.href="/member/my_order/all";
                } else if(response.status == "order_buy"){
                	alert(response.msg);
                }else if(response.status == "ok"){
                	window.location.href="/pay/product/"+response.id;
                }else if(response.status == 'no_login'){
                	seajs.use('module/nahaoCommon/commonLogin',function(_c){
                		_c.cLogin();
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
                if (response.status == "del_ok") {
                    alert(response.msg);
                    window.location.reload();
                } else if(response.status == "del_no"){
                	alert(response.msg);
                } else if(response.status == "del_error"){
                	alert(response.msg);
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
                if (response.status == "cancel_ok") {
                    alert(response.msg);
                    window.location.reload();
                } else if(response.status == "cancel_error"){
                	alert(response.msg);
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
        $(".outlineList li").mouseover(function (){
            $(this).find(".cListHid").show();     
        });
        $(".outlineList li").mouseout(function (){
            $(this).find(".cListHid").hide();
        });
        $(".evaluBtn").click(function (){               
            _popUp.popUp('.evaluHtml');
            exports.starClick();
            require("module/classRoom/valid").evaluForm();
        })
    }
    
	//评论 几颗星
	exports.starClick = function (){
		//var ind = true;
		$(".evalu .starBg span").click(function (){
			//if(ind){
				var _index = $(".evalu .starBg span").index($(this));
				for(var i=0;i<_index+1;i++){
					$(".evalu .starBg span").eq(i).addClass("cStar");
				}
				//ind = false;
			//}
		});
	}
    
        //发送验证码
    exports.sendValidateCode = function (){
        $('.sendPhoneCode').click(function() {
            var _this = $(this);
            var phone = $("input[name='phone']").val();
            var verify_type = $("input[name='verify_type']").val();
            if(!(phone)) {
                alert('请填写手机号');
                return false;
            } else if(!(/\d{11}/.test(phone))) {
                alert('请输入正确的手机号')
                return fasle;
            }
            $.ajax({
                url : 'http://www.nahaodev.com/register/send_captcha',
                type : 'post',
                data : {'phone' : phone, 'type' : verify_type},
                dataType : 'json',
                success : function (result) {
                    if(result.status == 'error') {
                        alert(result.msg);
                    }
                    //手机验证倒计时
                    require("module/common/method/countDown").countDown(_this);
                }
            }
            );
        });
    }
});