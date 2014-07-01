/**
 * edit lesson logic
 * @author yanrui@tizi.com
 */
define(function (require, exports) {

    var sort_url = '/lesson/sort';

    //lesson_create
    exports.bind_everything = function () {

        //edit lesson modal
        $("#btn_lesson_create,.btn_lesson_update").on('click',function(){
            //clear all input and bind
            $("#lesson_name").val('');
            $("#lesson_is_chapter").attr('checked',false);
            $("#btn_lesson_submit").unbind("click");

            //get action type
            var action = $(this).data("action");
            var course_id = $("#course_id").val();

            //show edit data
            if(action=='update'){
//                console.log($(this).data("is_chapter"));
                var td = $(this).parent().parent().children();
                var update_parent_id = $(td[0]);
                lesson_id = $(this).data("lesson_id");
                $("#lesson_name").val($(td[1]).text());
                $("#lesson_is_chapter").attr('checked',$(this).data("is_chapter")==1 ? true : false);
            }
            //get chapters
//            var get_chapters_url = '/lesson/chapters/'+course_id;
//            $.get(get_chapters_url,function(response){
//                if(response.data){
//                    var chapters_html = '<select class="form-control input-md" id="lesson_parent_id" name="parent_id">'
//                    $.each(response.data,function(){
//                        chapters_html += '<option value="'+this.id+'">'+this.title+'</option>';
//                    });
//                    chapters_html += '</select>';
//                }
//                console.log(lesson_list_chapters);
//                $("#lesson_list_chapters").html(chapters_html);
//            });

            //show modal
            $("#modal_lesson_edit").modal();

            //submit lesson
            $("#btn_lesson_submit").on("click",function(){
                var url = $(this).data("action");
                var data = {
                    'action' : action,
                    'course_id' : course_id,
                    'lesson_name' : $("#lesson_name").val(),
                    'lesson_is_chapter' : $("#lesson_is_chapter").attr("checked")=="checked" ? 1 : 0
                };
//                console.log($("#lesson_is_chapter").attr("checked"));
//                console.log(data);
//                return false;
                if(action=='update'){
                    data['lesson_id'] = lesson_id;
                }
//                console.log(data);
//                return false;
                $.post(url,data,function(response){
                    if(response){
//                        console.log(response);return false;
                        alert(response.msg);
                        if(response.status=="ok"){
//                            console.log(action);
//                            console.log( response.id);
//                            console.log(action=="create" && response.id);
//                            console.log(data['lesson_is_chapter']);
//                            console.log(update_parent_id.data("is_chapter"));
                            if(action=="create"){
                                var new_tr = '<tr class="lesson_list">';
                                new_tr += '<td data-is_chapter="'+data['lesson_is_chapter']+'">'+response.id+'</td></tr>';
//                                console.log(new_tr);
                                $("#lesson_list_tbody").append(new_tr);
                            }else{
                                update_parent_id.data("is_chapter",data['lesson_is_chapter']);
                            }
//                            console.log(update_parent_id.data("is_chapter"));
//                            console.log('1111');
//                            console.log(update_parent_id.data("parent_id"));
                            exports.lessons_sort();
//                            window.location.reload();

                        }
                    }
                });
            })
        });

        //sort lessons
        $("#btn_lesson_sort").on("click",function(){
            exports.lessons_sort();
            return false;
        });

        //delete lesson
        $("#btn_lesson_delete").on("click",function(){

        });

        //dragsort
        $("tbody").dragsort({ dragSelector: "tr", dragBetween: true, dragEnd: exports.save_order});
    }

    exports.save_order = function () {
        var data = $("tbody tr").map(function() { return $(this).children().html(); }).get();
        exports.lessons_sort();
//        $('.lesson_list').each(function(){
//            var ttddss = $(this).children();
//            console.log($(ttddss[0]).text());
//        });
    }

    exports.lessons_sort = function(){
        var url = sort_url;
        var arr = new Array();
        $(".lesson_list").each(function(){
            var tds = $(this).children();
            var value = {
                'id' : $(tds[0]).text(),
                'is_chapter' : $(tds[0]).data("is_chapter")
            }
            arr.push(value);
        });
        var data = {
            'course_id' : $("#course_id").val(),
            'lessons' : arr
        };
        $.each(arr,function(){
            console.log(this.id+','+this.is_chapter);
        })
//return false;
        $.post(url,data,function(response){
//            window.location.reload();
        });
    }

});