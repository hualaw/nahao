define(function(require,exports){

    exports.load_everything = function () {

        //load datepicker
        $(".pick_date").datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })

        //load ckeditor
        if($("#nahao_description").length >0){
            CKEDITOR.replace('nahao_description');
        }

    }


    exports.bind_everything = function () {

        //get teacher list to select
        $("#round_edit_teacher_select_btn").on("click", function () {
            var modal = $("#round_edit_teacher_select_modal");
            var action = $(this).data('action');
            var data = {
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

        //select teacher to submit
        $("#round_edit_teacher_select_modal").on("click", '.submit_teacher', function () {
            var add_teacher_html = $("#round_edit_teacher_select_div").html();
//            var bttn = '<botton class="btn btn-primary submit_teacher" data-teacher_id="6">李四&nbsp;&nbsp;<button type="button" class="close delete_teacher_btn">&times;</button></botton>';
            $(this).removeClass("submit_teacher");
            $(this).addClass("selected_teacher");
            var delete_btn = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<botton class=\"btn-primary delete_teacher_btn\">x</botton>";
            $(this).append(delete_btn);
            $("#round_edit_teacher_select_div").html(this);
            $("#round_edit_teacher_select_div").append(add_teacher_html);
            $("#round_edit_teacher_select_modal").modal('hide');
        });

        //delete selected teacher
        $("#round_edit_teacher_select_div").on("click", '.delete_teacher_btn', function () {
            var result = confirm("删除该老师？");
            if(result==true){
                $($(this).parent()).remove();
            }
        });

        //append lesson input area
        $("#round_edit_lesson_add_div").on("click", '#round_edit_class_add_btn', function () {
            var class_input_html =
                '<div class="form-group">' +
                    '<label class="col-md-2 control-label"></label>' +
                    '<div class="col-md-6">' +
                    '<input type="text" class="form-control class" placeholder="">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                    '<label class="checkbox-inline">' +
                    '<input type="checkbox" class="class_chapter">是章' +
                    '</label>'+
                    '<label class="checkbox-inline">'+
                    '<botton class="btn btn-danger btn-xs delete_class_btn">x</botton>'+
                    '</label>'+
                    '</div>' +
                    '</div>';
            $("#round_edit_lesson_list_div").append(class_input_html);
        });

        //delete lesson input area
        $("#round_edit_class_list_div").on("click", '.delete_class_btn', function () {
            var result = confirm("删除该章节？");
            if(result==true){
                $($(this).parent().parent().parent()).remove();
            }
        });
    }

    //validate and post after click submit btn
    exports.submit_round = function () {

        $('#round_edit_submit_round').on("click", function () {
            var id = $("#id").val();
            var title = $("#title").val();
            var subtitle = $("#subtitle").val();
            var intro = $("#intro").val();
            var description = CKEDITOR.instances.nahao_description.getData();
            var students = $("#students").val();
            var subject = $("#subject").val();
            var course_type = $("#course_type").val();
            var reward = $("#reward").val();
            var price = $("#price").val();
            var video = $("#video").val();
            var img = $("#img_url").attr('src');
            var video = $("#video_url").text();

            var course_id = $("#course_id").val();
            var caps = $("#caps").val();
            var sale_price = $("#sale_price").val();
            var sell_begin_time = $("#sell_begin_time").val();
            var sell_end_time = $("#sell_end_time").val();
            var start_time = $("#start_time").val();
            var end_time = $("#end_time").val();


            //validate teachers
            var teachers = $("#round_edit_teacher_select_div .selected_teacher");
            var teacher_ids = new Array();
            if(teachers.length > 0){
                teachers.each(function(k,v){
                    teacher_ids.push($(v).data('teacher_id'));
                });
            }else{
                alert('请选择老师');
                return false;
            }

            //validate grade
            var grade_from = parseInt($("#grade_from").val());
            var grade_to = parseInt($("#grade_to").val());
            if(grade_to<grade_from){
                alert('年级错误');
                return false;
            }

            //validate classes
            var arr_classes = new Array();
            var classes = $(".class");
            var chapters = $(".class_chapter");
            var lesson_ids = $(".lesson_id");
            var courseware_ids = $(".courseware_id");
            var class_start_times = $(".class_start_time");
            var class_end_times = $(".class_end_time");

            if(classes.length > 0){
                classes.each(function(k,v){
                    arr_classes[k] = {
                        'name' : $(v).val(),
                        'is_chapter' : $(chapters[k]).attr("checked")=='checked' ? 1 : 0,
                        'lesson_id' : $(lesson_ids[k]).val(),
                        'courseware_id' : $(courseware_ids[k]).val(),
                        'start_time' : $(class_start_times[k]).val(),
                        'end_time' : $(class_end_times[k]).val()
                    };
                });
            }else{
                alert('请添加章节');
                return false;
            }

            var action = $('#round_edit_submit_round').data('action');

            var data = {
                'id' : id,
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
                'classes' : arr_classes,
                'teachers' : teacher_ids,

                'course_id' : course_id,
                'caps' : caps,
                'sale_price' : sale_price,
                'sell_begin_time' : sell_begin_time,
                'sell_end_time' : sell_end_time,
                'start_time' : start_time,
                'end_time' : end_time
            };
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