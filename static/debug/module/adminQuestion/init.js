define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    require('../../../common/ckeditor/ckeditor');
    var course = require('module/adminQuestion/question');
    require("module/adminCourse/upload").addUpload();//调用上传图片
    //填写联系方式 验证
    require("module/studentCart/valid").inforCheckForm();	
})