define(function(require,exports){
        //倒入jquery
        require("lib/cookies/0.0.1/jquery.cookies");
        // 公共select模拟
        require('select');
        //倒入dialog
        require("naHaoDialog");
        require("module/common/method/setSchool").init();
        /*
        if($(".popBox").length>0){
            require("module/common/method/popUp").popUp(".popBox");
        };
        */

        //判断当前页面时注册成功的关于我的页面
        if($('.writeInfo').length > 0){

            //我要开课验证
            var _valid = require("module/studentStartClass/valid");

            // 美化select
            $('select').jqTransSelect();
            // 美化radio
            $('input[type=radio]').jqTransRadio();
            // 美化checkBo
            $('input[type=checkbox]').jqTransCheckBox();

            // require("module/studentStartClass/edit").edit();
            //时间插件
            require("module/studentStartClass/datePlugin").addDatePlugin();
            //我要开课 试讲 信息 验证
            _valid.writeInfoForm();
        }
        if(!$.cookies.get("_nahao_shua")){
            $.cookies.set('_nahao_shua',"1",{ hoursToLive: 24*365 });
            window.location.reload();
        }
})
