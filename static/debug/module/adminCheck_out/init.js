define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    var accounts = require('module/adminCheck_out/accounts');
    accounts.accounts();
})