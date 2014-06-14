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

})