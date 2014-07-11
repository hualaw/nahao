define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/ckeditor/4.3/ckeditor');
    require('lib/jquery/1.8.2/jquery.js');

    var course = require('module/adminCourse/course');
    course.load_ckeditor();
    course.teacher_select();
    course.submit_teacher();
    course.delete_teacher();
//    course.add_lesson();
//    course.delete_lesson();
    course.submit_course();
    course.course_operation();
    course.select_nickname();


    var upload = require("module/adminCourse/upload");
    upload.addUpload();//调用上传图片
	upload.video_upload();//video_upload

})
