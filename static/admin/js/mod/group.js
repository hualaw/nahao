/**
 * group
 * @author yanrui@tizi.com
 */
$(function () {

    //create group
    $("#group_create_modal").on("click", '#btn_group_submit', function () {
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
        }, "json");
    });

    //active group
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
        }, "json");
    });


});