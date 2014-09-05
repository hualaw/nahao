define(function (require, exports) {
    $('#start_time').datetimepicker({
        format: "yyyy-MM-dd",
        language: 'cn',
        autoclose : true,
        inputMask: true,
        startView:2,
        minView:2,
        todayBtn:true
    })
})