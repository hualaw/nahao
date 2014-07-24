define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/tooltip.js');
    require('lib/bootstrap/popover.js');
    var count_classroom_num = require('module/adminCount_classroom_num/count_classroom_num');
    count_classroom_num.count_classroom_num();
})