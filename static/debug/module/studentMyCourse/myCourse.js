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
                $(this).addClass("ctimeOn");
            }
        })
    }
    //倒计时
    exports.countDown = function (){
        var timer = null;

        function countDown(){
            var oDate=new Date();
            oDate.setFullYear(2014,5,28);
            oDate.setHours(13,20,0);

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
});