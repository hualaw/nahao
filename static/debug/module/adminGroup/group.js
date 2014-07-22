define(function(require,exports){

    exports.bind_everything = function (){

        //group active
        $(".group_active").on("click", function () {
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

        //group edit create/update
        $(".btn_group_create,.btn_group_edit").on("click",function(){
            var action = $(this).data("action");
            if(action=="update"){
                var data = {
                    'group_id' : $(this).data("group_id")
                }
                $.get("/group/groups",data,function(response){
                    $("#modal_group_edit #group_id").val(response.data.id);
                    $("#modal_group_edit #group_name").val(response.data.name);
                    $("#modal_group_edit #group_status").attr("checked",response.data.status==1 ? true : false);
                })
            }
            $("#modal_group_edit").modal();
        });

        //group submit
        $("#btn_group_submit").on("click", function () {
            var url = $('#btn_group_submit').data('action');
            var data = {
                "id": $("#modal_group_edit #group_id").val(),
                "name": $("#modal_group_edit #group_name").val(),
                "status": $('#modal_group_edit #group_status').attr("checked")=="checked" ? 1 : 0
            };
            $.post(url, data, function (response) {
                console.log(response);
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            });
        });
        
        //group permission
        $('.permission').click(function(){
        	var status = this.checked ? 1 : 0;
        	var data = {
                    "pid": $(this).data("pid"),
                    "gid": $(this).data("gid"),
                    "status":status
                };
            $.post('/group/permission_group_set', data, function (res) {
                if (res.status == 1) {
                    alert(res.msg);
                }
            }, 'json');
        });
    }
});
