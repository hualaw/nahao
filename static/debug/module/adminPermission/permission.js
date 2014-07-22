define(function(require,exports){

    exports.bind_everything = function (){

        //permission active
        $(".permission_active").on("click", function () {
            var btn = $(this);
            var action = btn.data('action');
            var data = {
                permission_id: btn.data('permission_id'),
                status: btn.data('status')
            };
            $.post(action, data, function (response) {
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            });
        });

        //permission edit create/update
        $(".btn_permission_create,.btn_permission_edit").on("click",function(){
            var action = $(this).data("action");
            if(action=="update"){
                var data = {
                    'permission_id' : $(this).data("permission_id"),
                }
                $.get("/permission/permissions",data,function(response){
                    $("#modal_permission_edit #permission_id").val(response.data.id);
                    $("#modal_permission_edit #permission_name").val(response.data.name);
                    $("#modal_permission_edit #permission_controller").val(response.data.controller);
                    $("#modal_permission_edit #permission_action").val(response.data.action);
                    $("#modal_permission_edit #permission_status").attr("checked",response.data.status==1 ? true : false);
                })
            }else{
            	$("#modal_permission_edit #permission_id").val('');
            	$("#modal_permission_edit #permission_name").val('');
                $("#modal_permission_edit #permission_controller").val('');
                $("#modal_permission_edit #permission_action").val('');
            }
            
            $("#modal_permission_edit").modal();
        });

        //permission submit
        $("#btn_permission_submit").on("click", function () {
            var url = $('#btn_permission_submit').data('action');
            var data = {
                "id": $("#modal_permission_edit #permission_id").val(),
                "name": $("#modal_permission_edit #permission_name").val(),
                "controller": $("#modal_permission_edit #permission_controller").val(),
                "action": $("#modal_permission_edit #permission_action").val(),
                "status": $('#modal_permission_edit #permission_status').attr("checked")=="checked" ? 1 : 0
            };
            $.post(url, data, function (response) {
                console.log(response);
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            });
        });
    }
});
