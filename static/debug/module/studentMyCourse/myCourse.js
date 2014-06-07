define(function(require,exports){
    require("naHaoDialog");
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
            $.tiziDialog({
                //width:400,
                title:false,
                ok:false,
                icon:false,
                padding:0,
                content:$(".noteDia").html()
            });
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
    exports.countDown = function (){
        var timer = null;

        function countDown(){
            var oDate=new Date();
            array = $("#sell_endtime").val().split(" ");
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
                $(".countdown").html("已到时");
            }else{
                $(".countdown").html(days+'天   '+hours+'小时   '+mins+'’'+s+'“');
            }
        }
        countDown();
        timer = setInterval(countDown, 1000);   
    }
    
    //购买前--点击立即购买
    exports.soon_buy = function (){
        $("#soon_buy").click(function (){
            var url = '/pay/before_product/';
            var data = {
            	product_id: $('#product_id').val()
            };
            $.post(url, data, function (response) {
                if (response.status == "order_exist") {
                    //window.location.reload();
                	alert(response.msg);
                } else if(response.status == "order_buy"){
                	alert(response.msg);
                } else if(response.status == "ok"){
                	window.location.href="/pay/product/"+response.id;
                }
            }, "json");
        })
    }
    
    //购买前下面--点击购买课程
    exports.soon_buy_xia = function (){
        $("#soon_buy_xia").click(function (){
            var url = '/pay/before_product/';
            var data = {
            	product_id: $('#product_id_xia').val()
            };
            $.post(url, data, function (response) {
                if (response.status == "order_exist") {
                    //window.location.reload();
                	alert(response.msg);
                } else if(response.status == "order_buy"){
                	alert(response.msg);
                }else if(response.status == "ok"){
                	window.location.href="/pay/product/"+response.id;
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
    
});