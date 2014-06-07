define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    require('module/adminCommon/bootstrap-datetimepicker.min');
    var lecture = require('module/adminLecture/lecture');
    lecture.lecture();
})