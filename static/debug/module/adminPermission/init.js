define(function (require, exports) {
    require('lib/bootstrap/bootstrap.min');
    var permission = require('module/adminPermission/permission');
    permission.bind_everything();
})