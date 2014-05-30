define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    require('../../../common/ckeditor/ckeditor');
    var course = require('module/adminCourse/course');
    course.load_ckeditor();
    course.teacher_select();
})