define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/ckeditor/4.3/ckeditor');
    require("module/adminCourse/upload").addUpload();//调用上传图片
    //题目管理方法
    var adminQuestion = require('module/adminQuestion/question');
	adminQuestion.init();
})