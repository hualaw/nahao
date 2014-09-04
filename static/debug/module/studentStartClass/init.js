define(function(require,exports){	
	$(function (){
	if ($(".popBox").length>0) {
		require("module/common/method/popUp").popUp(".popBox");
	};

	//我要开课验证

	//判断当前页面时注册成功的关于我的页面
	if($('.writeInfo').length > 0){
        // 公共select模拟
        require('select');

        require("module/common/method/setSchool");

        //时间插件
        require("module/studentStartClass/datePlugin").addDatePlugin();

        //我要开课 试讲 信息 验证
        var _valid = require("module/studentStartClass/valid");
        _valid.writeInfoForm();
        /*
        require.async("select", function(ex) {
            // 美化select
            $('select').jqTransSelect();
            // 美化radio
            $('input[type=radio]').jqTransRadio();
            // 美化checkBox
            $('input[type=checkbox]').jqTransCheckBox();

            require("module/studentStartClass/edit").edit();
        });
        */
    }

	if($(".regTeacher").length){
    	//我要开课 老师注册验证
		_valid.teaRegForm();
	}
	})
})
