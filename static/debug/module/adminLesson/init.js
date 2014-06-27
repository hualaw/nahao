define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/dragsort/dragsort.min');

    var lesson_upload = require('module/adminLesson/upload');
    lesson_upload.upload();

    var lesson = require('module/adminLesson/lesson');
    lesson.bind_everything();
    lesson.lessons_sort();
})