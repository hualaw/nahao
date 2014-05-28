define(function(require,exports){
    require('module/adminCommon/bootstrap.min');
    var group = require('module/adminGroup/group');
    group.create_group();
    group.active_group();
})