define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    require('lib/ckeditor/4.3/ckeditor');
    var teacher = require('module/adminTeacher/teacher');
    teacher.teacher();
    teacher.load_ckeditor();
    var upload = require("module/adminTeacher/upload");
    upload.addUpload();
})