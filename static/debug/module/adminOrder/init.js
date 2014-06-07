define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    require('module/adminCommon/bootstrap-datetimepicker.min');
    var order = require('module/adminOrder/order');
    order.order();
})