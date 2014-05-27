define(function(require,exports){
    exports.aaaa=function(){
        //create admin
        $("#admin_create_modal").on("click", '#btn_admin_submit', function () {
            console.log('#btn_admin_submit');return false;
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
})