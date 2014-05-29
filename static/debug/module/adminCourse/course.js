define(function(require,exports){
    exports.load_ckeditor = function(){
        CKEDITOR.replace( 'description' );
    };
    exports.teacher_select = function(){
        $(".form-horizontal").on("click", '#course_edit_teacher_select_btn', function () {
            var modal = $("#course_edit_teacher_select_modal");
            var action = $(this).data('action');
            var data = {
            };
            $.post(action, data, function (response) {
//                return ;
                var teachers_html = '<div class="form-group">';
                $(response).each(function(k,v){
                    teachers_html += '<botton class="btn btn-primary submit_teacher" data-teacher_id="' + v.id + '">' + v.nickname + '</botton>&nbsp;';
                });
                teachers_html += '</div>';
                $("#teacher_list_area").html(teachers_html);
                modal.modal();
//                modal.modal();
//                console.log(teachers_html);return true;
//                alert(response.msg);
//                if (response && response.status == "ok") {
//                    window.location.reload();
//                }
            }, "json");
        });
    }


    exports.submit_teacher = function(){
        $(".course_edit_teacher_select_modal").on("click", '.submit_teacher', function () {
            var add_teacher_html = $("#course_edit_teacher_select_div").html();
            $("#course_edit_teacher_select_div").html(this+add_teacher_html);
            $(".course_edit_teacher_select_modal").modal('hide');
        });
    }
    //年级大小判断      每个的字数长度判断
});