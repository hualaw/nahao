define(function(require,exports){

    exports.bind_everything = function (){

        //active group
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

        $(".btn_group_create,.btn_group_edit").on("click",function(){
            var action = $(this).data("action");
            if(action=="update"){
                var data = {
                    'group_id' : $(this).data("group_id")
                }
                $.get("/group/group",data,function(response){
                    console.log(response);
                })
            }
        });


        //submit group
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
});
