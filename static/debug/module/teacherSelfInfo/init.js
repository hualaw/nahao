define(function(require,exports){
    require('select');
    if ($(".popBox").length>0) {
        require("module/teacherSelfInfo/popUp").popUp(".popBox");
    };
    // 美化select
//    $('select').jqTransSelect();
    // 美化radio
    $('input[type=radio]').jqTransRadio();
    // 美化checkBo
    $('input[type=checkbox]').jqTransCheckBox();
    var infoValid = require("module/teacherSelfInfo/valid");
    //判断当前页面时注册成功的关于我的页面
    if($('.writeInfo').length > 0){
        infoValid.teaClassValid();//试讲信息表单验证
        require("module/teacherSelfInfo/writeInfo").addDatePlugin();//添加日期控件
        require("module/teacherSelfInfo/edit").edit();//调用编辑器
    }
    //判断当前页面时注册成功的关于我的页面
    if($('.personInfo').length > 0){
        require("module/teacherSelfInfo/upload").addUpload();//调用上传图片
        infoValid.teaInfoValid();//个人资料表单验证
        infoValid.teaPassValid();//修改密码表单验证
    }
    if($("#province").length > 0) {
        require("module/teacherSelfInfo/area").change_area();
    }
})