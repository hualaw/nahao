define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    var teacher = require('module/adminTeacher/teacher');
    //require('module/common/method/setSchool');
    teacher.teacher();
})