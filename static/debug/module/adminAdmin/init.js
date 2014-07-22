define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    var admin = require('module/adminAdmin/admin');
    admin.create_admin();
    admin.active_admin();
    admin.password();
    admin.admin_group();
})