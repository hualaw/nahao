define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    require('module/adminCommon/bootstrap-datetimepicker.min');
    require('../../lib/ckeditor/4.3/ckeditor');

    var round = require('module/adminRound/round');
    round.load_everything();
    round.bind_everything();
    round.submit_round();
})