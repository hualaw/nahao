define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    require('../../../common/ckeditor/ckeditor');
//    require('../../../common/ckeditor/adapters/CKSource');
    var course = require('module/adminCourse/course');
    course.load_ckeditor();
    course.teacher_select();
    course.submit_teacher();
    course.delete_teacher();
    course.add_lesson();
    course.submit_course();
    require("module/adminCourse/upload").addUpload();//调用上传图片

//    var upload = require('module/adminCourse/ajaxfileupload');
//    upload.add_fileupload();
//    upload.upload_img();



})