define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    var tick = require('module/adminTickling/tickling');
    tick.tick();
})