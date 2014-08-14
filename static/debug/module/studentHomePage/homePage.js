define(function (require, exports) {

    //首页页面我的课程跳转
    exports.skip = function () {
        $(".courseBox").on("click", '.rotateBox', function () {
            var url = $(this).data('action');
            window.open(url);
        });
    }
    //大图轮播
    exports.roll = function () {
        var baseSrc = $(".qiniu").val();

        for (var i = 0; i < $(".focus_photo_class").length; i++) {
            $(".rollList").append('<li class="fl">' +
                '<a href="' + $(".focus_photo_class").eq(i).val().split(",")[1] + '" target="_blank" style="background:url(' + baseSrc + $(".focus_photo_class").eq(i).val().split(",")[0] + ') center top no-repeat ' + $(".focus_photo_class").eq(i).val().split(",")[2] + '"></a>' +
                '</li>');
            $(".rollNav").append('<li class="fl"></li>');
            if (i == 0) {
                $(".rollList li").eq(0).addClass("rollshow");
                $(".rollNav li").eq(0).addClass("active");
            }
            var $navLi = $(".rollNav li"),
                $conLi = $(".roll ul li");
        }
        //首页 大图滚动

        var ind = 0,
            timer = null,
            timer2 = null;

        function oAnimate() {
            $navLi.removeClass("active");
            $navLi.eq(ind).addClass("active");

            $conLi.removeClass("rollshow").stop().animate({opacity: 0});
            $conLi.eq(ind).addClass("rollshow").stop().animate({opacity: 1});
        }

        function move() {
            //console.log(ind);
            ind++;
            if (ind >= $conLi.length) {
                ind = 0
            }
            oAnimate();
        }

        function otimer() {
            timer = setInterval(move, 5000);
            //console.log(ind);
        }

        otimer();
        function mouseObj(obj) {
            obj.mouseover(function () {
                clearInterval(timer);
                clearTimeout(timer2);
                ind = $(this).index();

                oAnimate();
            });
            obj.mouseout(function () {
                timer2 = setTimeout(function () {
                    otimer();
                }, 2000);
            });
        }

        mouseObj($navLi);
        mouseObj($conLi);
    }
})