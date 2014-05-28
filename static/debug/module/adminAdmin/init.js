define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    var admin = require('module/adminAdmin/admin');
    admin.create_admin();
    admin.active_admin();
})