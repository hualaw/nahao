define(function(require,exports){
    require("select");
    // 美化select
    $('select').jqTransSelect();
    var orderList = require("module/teacherOrderList/teacherOrderList");
    if($(".orderList_search").length){
        orderList.addDatePlugin();    //加载调用时间方法
    }
})