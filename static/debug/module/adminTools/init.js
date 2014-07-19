define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/ckeditor/4.3/ckeditor');
    //题目管理方法
    var adminTools = require('module/adminTools/tools');
	adminTools.init();
})