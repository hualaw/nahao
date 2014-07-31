define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    require('lib/ckeditor/4.3/ckeditor');

    var upload = require("module/adminRound/upload");
    upload.addUpload();//调用上传图片
    var round = require('module/adminRound/round');
    round.load_everything();
    round.bind_everything();
    round.submit_round();
})