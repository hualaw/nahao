define(function (require, exports) {

    exports.load_everything = function () {

        //load datepicker
        $(".pick_date").datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose: true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })

        //load ckeditor
        if ($("#nahao_description").length > 0) {
            CKEDITOR.replace('nahao_description', { toolbar: 'Basic', height: 300, width: 700});
        }

    }


    exports.bind_everything = function () {

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

        //round_list update status
        $('.round_operation').on("click", function () {
            var url = $(this).data('action');
            var data = {
                'round_id': $(this).data('round_id'),
                'type': $(this).data('type'),
                'status': $(this).data('status')
            };
            $.post(url, data, function (response) {
//                console.log(response);
                if (response) {
                    alert(response.msg);
                    if (response.status == 'ok') {
                        window.location.reload();
                    }
                } else {
                    alert('系统错误');
                }
            });
        });

        //get teacher list to select
        $("#round_edit_teacher_select_btn").on("click", function () {
            var arr_teacher_ids = new Array();
            $(".selected_teacher").each(function () {
                arr_teacher_ids.push($(this).data("teacher_id"));
            });
            var modal = $("#round_edit_teacher_select_modal");
            var action = $(this).data('action');
            var data = {
                'teacher_ids': arr_teacher_ids
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
            if (result == true) {
                $($(this).parent()).remove();
            }
        });

        //append lesson input area
//        $("#round_edit_lesson_add_div").on("click", '#round_edit_class_add_btn', function () {
//            var class_input_html =
//                '<div class="form-group">' +
//                    '<label class="col-md-2 control-label"></label>' +
//                    '<div class="col-md-6">' +
//                    '<input type="text" class="form-control class" placeholder="">' +
//                    '</div>' +
//                    '<div class="col-md-2">' +
//                    '<label class="checkbox-inline">' +
//                    '<input type="checkbox" class="class_chapter">是章' +
//                    '</label>'+
//                    '<label class="checkbox-inline">'+
//                    '<botton class="btn btn-danger btn-xs delete_class_btn">x</botton>'+
//                    '</label>'+
//                    '</div>' +
//                    '</div>';
//            $("#round_edit_lesson_list_div").append(class_input_html);
//        });

        //delete lesson input area
//        $("#round_edit_class_list_div").on("click", '.delete_class_btn', function () {
//            var result = confirm("删除该章节？");
//            if(result==true){
//                $($(this).parent().parent().parent()).remove();
//            }
//        });
    }

    //validate and post after click submit btn
    exports.submit_round = function () {

        $('#round_edit_submit_round').on("click", function () {
            var id = $("#id").val();
            var course_id = $("#course_id").val();

            var title = $("#title").val();
            if (!title || title == 'undefined') {
                alert("班次名称不能为空");
                return false;
            }
            var sequence = parseInt($("#sequence").val());
            if (sequence < 0 || sequence == 'undefined' || isNaN(sequence)) {
                alert("序列不能为空");
                return false;
            }
            var education_type = parseInt($("#education_type").val());
            if (education_type < 0 || education_type == 'undefined' || isNaN(sequence)) {
                alert("教育类型不能为空");
                return false;
            }
            var material_version = parseInt($("#material_version").val());
            if (material_version < 0 || material_version == 'undefined' || isNaN(material_version)) {
                alert("教材版本不能为空");
                return false;
            }
            var subject = parseInt($("#subject").val());
            if (subject < 0 || subject == 'undefined' || isNaN(subject)) {
                alert("学科辅导科目不能为空");
                return false;
            }
            var quality = parseInt($("#quality").val());
//            console.log(quality);return false;
            if (quality < 0 || quality == 'undefined' || isNaN(quality)) {
                alert("素质教育科目不能为空");
                return false;
            }
            var course_type = parseInt($("#course_type").val());
            if (course_type < 0 || course_type == 'undefined' || isNaN(course_type)) {
                alert("课程类型不能为空");
                return false;
            }

            //validate teachers
            var teachers = $("#round_edit_teacher_select_div .selected_teacher");
            var teacher_ids = new Array();
            if (teachers.length > 0) {
                teachers.each(function (k, v) {
                    teacher_ids.push($(v).data('teacher_id'));
                });
            } else {
                alert('请选择老师');
                return false;
            }
            var stage = parseInt($("#stage").val());
            if (stage < 0 || stage == 'undefined' || isNaN(stage)) {
                alert("学段不能为空");
                return false;
            }
            //validate grade
            var grade_from = parseInt($("#grade_from").val());
            var grade_to = parseInt($("#grade_to").val());
            if (grade_from < 0 || grade_from == 'undefined' || isNaN(grade_from) || grade_to < 0 || grade_to == 'undefined' || isNaN(grade_to) || grade_to < grade_from) {
                alert('年级错误');
                return false;
            }
            var reward = parseFloat($("#reward").val());
            if (reward < 0 || reward == 'undefined' || isNaN(reward)) {
                alert("课酬不能为空");
                return false;
            }
            var price = parseFloat($("#price").val());
            if (price < 0 || price == 'undefined' || isNaN(price)) {
                alert("价格不能为空");
                return false;
            }
            var sale_price = parseFloat($("#sale_price").val());
            if (sale_price < 0 || sale_price == 'undefined' || isNaN(sale_price)) {
                alert("促销价格不能为空");
                return false;
            }
            //validate price
            if (sale_price > price) {
                alert("促销价格不能大于价格");
                return false;
            }
            var caps = parseInt($("#caps").val());
            if (caps < 0 || caps == 'undefined' || isNaN(caps)) {
                alert("上限人数不能为空");
                return false;
            }
            var extra_bought_count = parseInt($("#extra_bought_count").val());
            if (extra_bought_count < 0 || extra_bought_count == 'undefined' || isNaN(extra_bought_count)) {
                alert("额外人数不能为空");
                return false;
            }
            var subtitle = $("#subtitle").val();
            if (!subtitle || subtitle == 'undefined') {
                alert("一句话简介不能为空");
                return false;
            }
            var intro = $("#intro").val();
            if (!intro || intro == 'undefined') {
                alert("课程简介不能为空");
                return false;
            }
            var students = $("#students").val();
            if (!students || students == 'undefined') {
                alert("适合人群不能为空");
                return false;
            }
            $("#nahao_description_new").val(CKEDITOR.instances.nahao_description.getData());
            var description = $("#nahao_description_new").val();
//            var description = CKEDITOR.instances.nahao_description.getData();
            if (!description || description == 'undefined') {
                alert("课程提要不能为空");
                return false;
            }
            var img = $("#img_url").attr('src');
            var sell_begin_time = $("#sell_begin_time").val();
            if (!sell_begin_time || sell_begin_time == 'undefined') {
                alert("销售开始时间不能为空");
                return false;
            }
            var sell_end_time = $("#sell_end_time").val();
            if (!sell_end_time || sell_end_time == 'undefined') {
                alert("销售结束时间不能为空");
                return false;
            }
            //validate sell time
            if (sell_begin_time >= sell_end_time) {
                alert("销售开始时间不能晚于销售结束时间");
                return false;
            }
            var is_test = $("#is_test").attr("checked") == 'checked' ? 1 : 0;
            var is_live = $("#is_live").attr("checked") == 'checked' ? 1 : 0;
//            var start_time = $("#start_time").val();
//            if(!start_time && start_time!='undefined'){
//                alert("开始时间不能为空");
//                return false;
//            }
//            var end_time = $("#end_time").val();
//            if(!end_time && end_time!='undefined'){
//                alert("结束时间不能为空");
//                return false;
//            }
//            if(sell_end_time > start_time){
//                alert("销售结束时间不能晚于授课开始时间");
//                return false;
//            }
//            if(start_time >= end_time){
//                alert("开始时间不能晚于结束时间");
//                return false;
//            }


            //validate classes
//            var arr_classes = new Array();
//            var classes = $(".class");
//            var chapters = $(".class_chapter");
//            var lesson_ids = $(".lesson_id");
//            var courseware_ids = $(".courseware_id");
//            var class_start_times = $(".class_start_time");
//            var class_end_times = $(".class_end_time");

//            if(classes.length > 0){
//                classes.each(function(k,v){
//                    arr_classes[k] = {
//                        'name' : $(v).val(),
//                        'is_chapter' : $(chapters[k]).attr("checked")=='checked' ? 1 : 0,
//                        'lesson_id' : $(lesson_ids[k]).val(),
//                        'courseware_id' : $(courseware_ids[k]).val(),
//                        'start_time' : $(class_start_times[k]).val(),
//                        'end_time' : $(class_end_times[k]).val()
//                    };
//                });
//            }else{
//                alert('请添加章节');
//                return false;
//            }

            var action = $('#round_edit_submit_round').data('action');

            var data = {
                'id': id,
                'course_id': course_id,
                'title': title,
                'sequence': sequence,
                'education_type': education_type,
                'material_version': material_version,
                'subject': subject,
                'quality': quality,
                'course_type': course_type,
                'teachers': teacher_ids,
                'stage': stage,
                'grade_from': grade_from,
                'grade_to': grade_to,
                'reward': reward,
                'price': price,
                'sale_price': sale_price,
                'caps': caps,
                'extra_bought_count': extra_bought_count,
                'subtitle': subtitle,
                'intro': intro,
                'students': students,
                'description': description,
                'img': img,
                'sell_begin_time': sell_begin_time,
                'sell_end_time': sell_end_time,
                'is_test': is_test,
                'is_live': is_live
//                'video' : video,
//                'classes' : arr_classes,
            };
//            console.log(data);
//            return false;
            $.post(action, data, function (response) {
                alert(response.msg);
                if (response.status == 'ok') {
                    window.location.href = response.redirect;
                } else {
                    return false;
                }
            });
        });

    }


});