define(function(require,exports){
    if ($(".popBox").length>0) {
        require("module/teacherSelfInfo/popUp").popUp(".popBox");
    };
    //判断当前页面时注册成功的关于我的页面
    if($('.writeInfo').length > 0){
        // 美化select
        $('select').jqTransSelect();
        // 美化radio
        $('input[type=radio]').jqTransRadio();
        // 美化checkBo
        $('input[type=checkbox]').jqTransCheckBox();
        require("module/teacherSelfInfo/edit").edit();//调用编辑器
    }
    //判断当前页面时注册成功的关于我的页面
    if($('.personInfo').length > 0){
        // 美化select
        $('select').jqTransSelect();
        // 美化radio
        $('input[type=radio]').jqTransRadio();
        // 美化checkBo
        $('input[type=checkbox]').jqTransCheckBox();
        require("module/teacherSelfInfo/uploadfile").addUploadCredit();//调用上传图片
    }
})