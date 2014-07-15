define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    var count_time_num = require('module/adminCount_time_num/count_time_num');
    count_time_num.count_time_num();
})