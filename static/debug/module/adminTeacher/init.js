define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    var teacher = require('module/adminTeacher/teacher');
    teacher.teacher();

    var upload = require("module/adminTeacher/upload");
    upload.addUpload();
})