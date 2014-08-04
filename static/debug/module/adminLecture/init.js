define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    var lecture = require('module/adminLecture/lecture');
    lecture.lecture();
    var lecture_upload = require('module/adminLecture/upload');
    lecture_upload.upload();
})