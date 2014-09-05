define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    var record_upload = require('module/adminResource/upload');
    record_upload.upload();

//    var lesson = require('module/adminResource/lesson');
//    lesson.bind_everything();
//    lesson.active_lesson();
})