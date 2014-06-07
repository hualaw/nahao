define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
//    require('../../lib/ckeditor/4.3/ckeditor');
//    require('../../../common/ckeditor/adapters/CKSource');

    var lesson = require('module/adminLesson/upload');
    lesson.upload();
})