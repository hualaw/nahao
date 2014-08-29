/**
 * edit course logic
 * @author yanrui@tizi.com
 */
define(function (require, exports) {

    //course_operation
    exports.course_operation = function () {
        //operation modal
        $('.course_operation').on("click",function(){
            var url = $(this).data('action');
            var data = {
                'course_id' : $(this).data('course_id'),
                'status' : $(this).data('status')
            };
            $.post(url,data,function(response){
//                console.log(response);
                if(response){
                    alert(response.msg);
                    if(response.status=='ok'){
                        window.location.reload();
                    }
                }else{
                    alert('系统错误');
                }
            });
        });
    }

    exports.bind_everything = function(){

        var education_type = $('#education_type').val();
        if(education_type==1){
            $("#quality").attr("disabled",true);
        }else if(education_type==2){
            $("#subject").attr("disabled",true);
        }else{
            $("#quality,#subject").val(0);
        }

        //change education_type
        $('#education_type').on("change",function(){
            var education_type = $(this).val();
            $("#quality,#subject").removeAttr("disabled");
            $("#quality,#subject").val(0);
            if(education_type==1){
                $("#quality").attr("disabled",true);
            }else if(education_type==2){
                $("#subject").attr("disabled",true);
            }else{
                $("#quality,#subject").val(0);
            }

        });
    }

    //load ckeditor
    exports.load_ckeditor = function () {
        if($("#nahao_description").length >0){
            CKEDITOR.replace('nahao_description',{ toolbar:'Basic',extraPlugins:'nahaovideo', height:300 ,width:750});
        }
    };

    //get teacher list to select
    exports.teacher_select = function () {
        $(".form-horizontal").on("click", '#course_edit_teacher_select_btn', function () {
            var arr_teacher_ids = new Array();
            $(".selected_teacher").each(function(){
                arr_teacher_ids.push($(this).data("teacher_id"));
            });
            var modal = $("#course_edit_teacher_select_modal");
            var action = $(this).data('action');
            var data = {
                'teacher_ids' : arr_teacher_ids
            };
            $.post(action, data, function (response) {
                var teachers_html = '<div class="form-group">';
                $(response).each(function (k, v) {
                    teachers_html += '<botton class="btn btn-primary submit_teacher" data-teacher_id="' + v.id + '">' + v.nickname + '</botton>&nbsp;';
                });
                teachers_html += '</div>';
                $("#teacher_list_area").html(teachers_html);
                modal.modal();
            }, "json");
        });
    }

    //select teacher to submit
    exports.submit_teacher = function () {

        $("#course_edit_teacher_select_modal").on("click", '.submit_teacher', function () {
            var add_teacher_html = $("#course_edit_teacher_select_div").html();
//            var bttn = '<botton class="btn btn-primary submit_teacher" data-teacher_id="6">李四&nbsp;&nbsp;<button type="button" class="close delete_teacher_btn">&times;</button></botton>';

            $(this).removeClass("submit_teacher");
            $(this).addClass("selected_teacher");
            var delete_btn = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<botton class=\"btn-primary delete_teacher_btn\">x</botton>";
            $(this).append(delete_btn);
            $("#course_edit_teacher_select_div").html(this);
            $("#course_edit_teacher_select_div").append(add_teacher_html);
            $("#course_edit_teacher_select_modal").modal('hide');
        });
    }

    //delete selected teacher
    exports.delete_teacher = function () {
        $("#course_edit_teacher_select_div").on("click", '.delete_teacher_btn', function () {
            var result = confirm("删除该老师？");
            if(result==true){
                $($(this).parent()).remove();
            }
        });
    }

    exports.select_nickname=function(){
        $("#select_nick").click(function() {
            $('#teacher_list_area').html('');
            $.ajax({
                type:"post",
                url:"/teacher/select_nickname",
                data:"nickname="+$('#nickname').val(),
                success:function(msg){
                    var nick=eval(msg);
                    $.each(nick,function(index,d){
                        $('#teacher_list_area').html("<botton class='btn btn-primary submit_teacher' data-teacher_id="+d.id+">"+d.nickname+"</botton>");
                    })
                }
            })
        });
    }

    //append lesson input area
//    exports.add_lesson = function () {
//
//        $("#course_edit_lesson_add_div").on("click", '#course_edit_lesson_add_btn', function () {
//            var lesson_input_html =
//                '<div class="form-group">' +
//                    '<label class="col-md-2 control-label"></label>' +
//                    '<div class="col-md-6">' +
//                        '<input type="text" class="form-control lesson" placeholder="">' +
//                    '</div>' +
//                    '<div class="col-md-2">' +
//                        '<label class="checkbox-inline">' +
//                            '<input type="checkbox" class="lesson_chapter">是章' +
//                        '</label>'+
//                        '<label class="checkbox-inline">'+
//                            '<botton class="btn btn-danger btn-xs delete_lesson_btn">x</botton>'+
//                        '</label>'+
//                    '</div>' +
//                '</div>';
//            $("#course_edit_lesson_list_div").append(lesson_input_html);
//        });
//
//    }

    //delete lesson input area
//    exports.delete_lesson = function (){
//
//        $("#course_edit_lesson_list_div").on("click", '.delete_lesson_btn', function () {
//            var result = confirm("删除该章节？");
//            if(result==true){
//                $($(this).parent().parent().parent()).remove();
//            }
//        });
//
//    }

    //validate and post after click submit btn
    exports.submit_course = function () {

        $(".form-group").on("click", '#course_edit_submit_course', function () {
            var id = $("#id").val();
            var title = $("#title").val();
            var education_type = $("#education_type").val();
            var material_version = $("#material_version").val();
            var subject = $("#subject").val();
            var quality = $("#quality").val();
            var course_type = $("#course_type").val();
            //validate teachers
            var teachers = $("#course_edit_teacher_select_div .selected_teacher");
            var teacher_ids = new Array();
            if(teachers.length > 0){
                teachers.each(function(k,v){
                    teacher_ids.push($(v).data('teacher_id'));
                });
            }else{
                alert('请选择老师');
                return false;
            }
            var stage = $("#stage").val();
            //validate grade
            var grade_from = parseInt($("#grade_from").val());
            var grade_to = parseInt($("#grade_to").val());
            if(grade_to<grade_from){
                alert('年级错误');
                return false;
            }
            var reward = $("#reward").val();
            var price = $("#price").val();
            var subtitle = $("#subtitle").val();
            var intro = $("#intro").val();
            var students = $("#students").val();
            $("#nahao_description_new").val(CKEDITOR.instances.nahao_description.getData());
            var description = $("#nahao_description_new").val();
//            var description = CKEDITOR.instances.nahao_description.getData();
            var img = $("#img_url").attr('src');
//            var video = $("#video").val();
            var video = $("#video_url").text();

            //validate lessons
//            var arr_lessons = new Array();
//            var lessons = $(".lesson");
//            var chapters = $(".lesson_chapter");
//            if(lessons.length > 0){
//                lessons.each(function(k,v){
//                    arr_lessons[k] = {'name' : $(v).val(),'is_chapter' : $(chapters[k]).attr("checked")=='checked' ? 1 : 0};
//                });
//            }else{
//                alert('请添加章节');
//                return false;
//            }

            var action = $('#course_edit_submit_course').data('action');

            var data = {
                'id' : id,
                'title' : title,
                'education_type' : education_type,
                'material_version' : material_version,
                'subject' : subject,
                'quality' : quality,
                'course_type' : course_type,
                'teachers' : teacher_ids,
                'stage' : stage,
                'grade_from' : grade_from,
                'grade_to' : grade_to,
                'subtitle' : subtitle,
                'intro' : intro,
                'description' : description,
                'students' : students,
                'reward' : reward,
                'price' : price,
                'img' : img,
                'video' : video
            };
//            console.log(encodeURI(description));
//            console.log(data);
//            return false;
            $.post(action, data, function (response) {
                alert(response.msg);
                if(response.status=='ok'){
                    window.location.href = response.redirect;
                }else{
                    return false;
                }
            });
        });

    }
});