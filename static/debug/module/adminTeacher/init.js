define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    require('module/adminCommon/bootstrap-datetimepicker.min');
    var teacher = require('module/adminTeacher/teacher');
    //require('module/common/method/setSchool');
    teacher.teacher();
})