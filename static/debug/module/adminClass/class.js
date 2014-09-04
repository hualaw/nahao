/**
 * class logic
 * @author yanrui@tizi.com
 */
define(function (require, exports) {

    var sort_url = '/classes/sort';

    exports.bind_everything = function () {

        //class edit modal
        $(".btn_class_edit").on("click",function(){
            var class_id = $(this).data("class_id");
            var is_first = $(this).data("is_first");
            var is_last = $(this).data("is_last");
            var round_id = $("#class_list_round_id").data("round_id");
            var tds = $(this).parent().parent().children();
//            console.log(tds);
//            $(tds[3]).text();
//            $(tds[4]).text();
            $("#class_title").val($(tds[2]).text());
            $("#class_begin_time").val($(tds[4]).text());
            $("#class_end_time").val($(tds[5]).text());
            $("#modal_class_edit").modal();
            //class update
            $("#btn_class_submit").on("click",function(){
                var title = $("#class_title").val();
                var begin_time = $("#class_begin_time").val();
                var end_time = $("#class_end_time").val();
                if(begin_time>=end_time){
                    alert('开始时间不能大于结束时间');
                    return false;
                }
                var url = $(this).data("action");
                var data = {
                    'round_id' : round_id,
                    'class_id' : class_id,
                    'is_first' : is_first,
                    'is_last' : is_last,
                    'title' : title,
                    'begin_time' : begin_time,
                    'end_time' : end_time
                };
//                console.log(data);
                $.post(url,data,function(response){
                    if(response){
                        alert(response.msg);
                        if(response.status=="ok"){
                            window.location.reload();
                        }
                    }
                    console.log(response);
                });
            });
        });

        $(".reload_courseware").on("click",function(){
            var url = '/classes/reload';
            var data = {
                'classroom_id' : $(this).data("classroom_id")
            };
            $.post(url,data,function(response){
                alert(response.msg)
                window.location.reload();
            })
        });

        //date picker in modal
        $("#class_begin_time,#class_end_time").datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })

        //sort classes
        $("#btn_class_sort").on("click",function(){
            exports.class_sort();
            return false;
        });

        //delete      class
        $(".btn_class_delete").on("click",function(){
            var result = confirm("确认删除该章节吗？");
            if(confirm){
                var tr = $(this);
                var class_id = tr.data("class_id");
                var url = '/classes/delete';
                var data = {
                    'class_id' : class_id
                };
                $.post(url,data,function(response){
                    alert(response.msg);
                    if(response.status=="ok"){
                        tr.parent().parent().remove();
//                        exports.class_sort();
                    }
                });
            }
        });

        //dragsort
//        $("tbody").dragsort({ dragSelector: "tr", dragBetween: true, dragEnd: exports.save_order});

    }

    exports.save_order = function () {
        var data = $("tbody tr").map(function() { return $(this).children().html(); }).get();
        exports.class_sort();
    }

    exports.class_sort = function(){
        var url = sort_url;
        var arr = new Array();
        $(".class_list").each(function(){
            var tds = $(this).children();
            var value = {
                'id' : $(tds[0]).text(),
                'is_chapter' : $(tds[0]).data("is_chapter")
            }
            arr.push(value);
        });
        var data = {
            'course_id' : $("#course_id").val(),
            'classes' : arr
        };
//        $.each(arr,function(){
//            console.log(this.id+','+this.is_chapter);
//        })
//return false;
        $.post(url,data,function(response){
            window.location.reload();
        });
    }

});