define(function(require,exports){
    //create group
    exports.create_group=function(){
        $("#btn_group_submit").on("click", function () {
            var modal = $("#group_create_modal");
            var btn = $('#btn_group_submit');

            var action = btn.data('action');
            var data = {
                name: modal.find('#name').val()
            };
            $.post(action, data, function (response) {
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            });
        });
    }

    //active group
    exports.active_group=function(){
        $(".table").on("click", '.group_active', function () {
            var btn = $(this);
            var action = btn.data('action');
            var data = {
                group_id: btn.data('group_id'),
                status: btn.data('status')
            };
            $.post(action, data, function (response) {
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            });
        });
    }
});
