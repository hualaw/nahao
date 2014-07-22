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

    //password_reset
    exports.password=function(){
        $("#btn_password").on("click", function () {
            var btn = $(this);
            var url = '/admin/password';
            var data = {
                'old_password' : $("#old_password").val(),
                'new_password' : $("#new_password").val(),
                'new_password_confirm' : $("#new_password_confirm").val()
            };
//            console.log(data);
//            return false;
            $.post(url, data, function (response) {
                console.log(response);
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            });
        });
    }
    
    exports.admin_group=function(){
    	$("#admin_group_modal").on("click",'#btn_group_submit', function () {
    		var admin_id = $(this).data('admin-id');
    		var group_id = $("#select_group").val();
    		var data = {
    			'admin_id': admin_id,
    			'group_id': group_id,
    		};
    		var url = '/admin/select_group';
    		$.post(url, data, function (response) {
                console.log(response);
                alert(response.msg);
                if (response && response.status == "ok") {
                    window.location.reload();
                }
            });
    	});
    }
    
    exports.load_admin_group=function(){
    	$(".admin_group").on("click",function () {
    		var admin_id = $(this).data('admin-id');
    		var group_id = $(this).data('group-id');
    		$("#btn_group_submit").attr("data-admin-id",admin_id);
    		$("#select_group option[value='"+group_id+"']").attr("selected","selected");
//    		$("#admin_group_modal").modal();
    	});
    }
})