define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    require('lib/ckeditor/4.3/ckeditor');
    var affiche = require('module/adminAffiche/affiche');
    affiche.load_ckeditor();
    affiche.affiche();
})