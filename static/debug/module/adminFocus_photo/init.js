define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    var focus_photo = require('module/adminFocus_photo/focus_photo');
    focus_photo.focus_photo();
    var upload = require("module/adminFocus_photo/upload");
    upload.addUpload();
})