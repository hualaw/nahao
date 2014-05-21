/**
 * admin_create
 * @author yanrui@tizi.com
 */
$(function () {
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
            if (response && response.status == "error") {
                alert(response.msg);
            } else {
                alert("添加成功");
                window.location.reload();
            }
        }, "json");
    });
});