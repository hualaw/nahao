define(function(require,exports){

    //active student
    exports.active_student=function(){
        $(".student_active").on("click", function () {
            var btn = $(this);
            var action = btn.data('action');
            var data = {
                user_id: btn.data('user_id'),
                status: btn.data('status')
            };
            $.post(action, data, function (response) {
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            }, "json");
        });
    }

    exports.bind_everything = function(){
        $('.register_time_select').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        });
    }

})