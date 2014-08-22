define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/ckeditor/4.3/ckeditor');
    var employment = require('module/adminEmployment/employment');
    employment.load_ckeditor();
    employment.employment();
})