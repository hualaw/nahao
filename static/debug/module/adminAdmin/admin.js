define(function(require,exports){
    //create admin
    exports.create_admin=function(){
        $("#admin_create_modal").on("click", '#btn_admin_submit', function () {
            var modal = $("#admin_create_modal");
            var btn = $('#btn_admin_submit');
            var action = btn.data('action');
            var data = {
                username: modal.find('#username').val(),
                phone: modal.find('#phone').val(),
                email: modal.find('#email').val()
            };
            $.post(action, data, function (response) {
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            }, "json");
        });
    }

    //active admin
    exports.active_admin=function(){
        $(".table").on("click", '.admin_active', function () {
            var btn = $(this);
            var action = btn.data('action');
            var data = {
                admin_id: btn.data('admin_id'),
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