define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/dragsort/dragsort.min');
    require('lib/preview/preview');
    require('lib/preview/preview_swf');

    var lesson_upload = require('module/adminLesson/upload');
    lesson_upload.upload();

    var lesson = require('module/adminLesson/lesson');
    lesson.bind_everything();
    lesson.active_lesson();
})