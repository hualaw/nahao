define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    require('../../lib/ckeditor/4.3/ckeditor');
//    require('../../../common/ckeditor/adapters/CKSource');

    var course = require('module/adminCourse/course');
    course.load_ckeditor();
    course.teacher_select();
    course.submit_teacher();
    course.delete_teacher();
    course.add_lesson();
    course.delete_lesson();
    course.submit_course();

    var upload = require("module/adminCourse/upload");
    upload.addUpload();//调用上传图片

})