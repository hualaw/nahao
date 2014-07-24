define(function(require,exports){
    require('lib/bootstrap/bootstrap.min');
    require('lib/bootstrap/bootstrap-datetimepicker.min');
    require('lib/dragsort/dragsort.min');
    require('lib/preview/preview');
    require('lib/preview/preview_swf');
    var classes = require('module/adminClass/class');
    classes.bind_everything();

    var class_upload = require('module/adminClass/upload');
    class_upload.upload();
//    require('lib/dragsort/dragsort.min');
//    $("tbody").dragsort({ dragSelector: "tr", dragBetween: true, dragEnd: saveOrder});
//
//    function saveOrder() {
//        var data = $("tbody tr").map(function() { return $(this).children().html(); }).get();
//        //	$("input[name=list1SortOrder]").val(data.join("|"));
//        $('.list_class_id').each(function(){
//
//            console.log($(this).text());
//            $(this).each(function(){
//                $(this).each(function(k,v){
//                var td = $(this);
//                    if(k==0){
//                    }
//                });
//            });
//        });
//    };
//    var lesson = require('module/adminClass/upload');
//    lesson.upload();
})