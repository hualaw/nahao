define(function (require, exports) {
    exports.load_ckeditor = function () {
        CKEDITOR.replace('nahao_description');
//        $('#nahao_description').ckeditor();
    };
    exports.teacher_select = function () {
        $(".form-horizontal").on("click", '#course_edit_teacher_select_btn', function () {
            var modal = $("#course_edit_teacher_select_modal");
            var action = $(this).data('action');
            var data = {
            };
            $.post(action, data, function (response) {
//                return ;
                var teachers_html = '<div class="form-group">';
                $(response).each(function (k, v) {
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

    exports.submit_teacher = function () {
        $("#course_edit_teacher_select_modal").on("click", '.submit_teacher', function () {
            var add_teacher_html = $("#course_edit_teacher_select_div").html();
//            var bttn = '<botton class="btn btn-primary submit_teacher" data-teacher_id="6">李四&nbsp;&nbsp;<button type="button" class="close delete_teacher_btn">&times;</button></botton>';

            $("#course_edit_teacher_select_div").html(this);
            $("#course_edit_teacher_select_div").append(add_teacher_html);
            $("#course_edit_teacher_select_modal").modal('hide');
        });
    }
    //年级大小判断      每个的字数长度判断
    exports.delete_teacher = function () {
        $("#course_edit_teacher_select_div").on("click", '.delete_teacher_btn', function () {
            alert(123);
        });
    }

    exports.add_lesson = function () {
        $("#course_edit_lesson_add_div").on("click", '#course_edit_lesson_add_btn', function () {
            var lesson_input_html =
                '<div class="form-group">' +
                    '<label class="col-md-2 control-label"></label>' +
                    '<div class="col-md-6">' +
                        '<input type="text" class="form-control lesson" placeholder="">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="checkbox-inline">' +
                            '<input type="checkbox" class="lesson_chapter">是章' +
                        '</label>'
                    '</div>' +
                '</div>';
            $("#course_edit_lesson_list_div").append(lesson_input_html);

        });
    }

    exports.submit_course = function () {
        $(".form-group").on("click", '#course_edit_submit_course', function () {

            var title = $("#title").val();
            var subtitle = $("#subtitle").val();
            var intro = $("#intro").val();
//            var description = $("#nahao_description").val();
            var description = CKEDITOR.instances.nahao_description.getData();
            var students = $("#students").val();
            var subject = $("#subject").val();
            var course_type = $("#course_type").val();
            var reward = $("#reward").val();
            var price = $("#price").val();
            var video = $("#video").val();
            var img = $("#img").val();

//            console.log(students);
//            console.log(description);return false;

            //验证老师
            var teachers = $("#course_edit_teacher_select_div .selected_teacher");
            var teacher_ids = new Array();
            if(teachers.length > 0){
               teachers.each(function(k,v){
                   teacher_ids.push($(v).data('teacher_id'));
                });
            }
            console.log(teachers);
            console.log( JSON.stringify(teacher_ids));
            return false;


            //验证年级
            var grade_from = parseInt($("#grade_from").val());
            var grade_to = parseInt($("#grade_to").val());
            if(grade_to<grade_from){
                alert('年级错误');
                return false;
            }

            //验证章节
            var arr_lessons = new Array();
            var lessons = $(".lesson");
            var chapters = $(".lesson_chapter");
            console.log(lessons);
            console.log(chapters);
            lessons.each(function(k,v){
                arr_lessons[k] = {'name' : $(v).val(),'is_chapter' : $(chapters[k]).attr("checked")=='checked' ? 1 : 0};
            });
//            console.log(arr_lessons);

            var action = $('#course_edit_submit_course').data('action');

            var data = {
                'title' : title,
                'subtitle' : subtitle,
                'intro' : intro,
                'description' : description,
                'students' : students,
                'subject' : subject,
                'course_type' : course_type,
                'reward' : reward,
                'price' : price,
                'video' : video,
                'img' : img,
                'grade_from' : grade_from,
                'grade_to' : grade_to,
                'lessons' : arr_lessons,
                'teachers' : teacher_ids
            };
            console.log(data);
            return false;
            $.post(action, data, function (response) {
                console.log(response);
            return false;
            });

        });
    }

    exports.validate = function(){
        var grade_from = $("#grade_from").val();
        var grade_to = $("#grade_to").val();
    }
});