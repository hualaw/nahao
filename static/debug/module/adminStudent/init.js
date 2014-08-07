define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    var admin = require('module/adminStudent/student');
    admin.active_student();
    admin.bind_everything();
})