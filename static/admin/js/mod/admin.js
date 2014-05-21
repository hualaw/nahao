/**
 * admin_create
 * @author yanrui@tizi.com
 */
$(function () {

    $('#admin_datetimepicker').datetimepicker({
        format: "yyyy-MM-dd hh:ii",
        language: 'cn',
        autoclose : true,
//        pickDate: true,
//        pickTime: true,
        hourStep: 1,
        minuteStep: 15,
        secondStep: 30,
        inputMask: true
    });

    //create admin
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
});