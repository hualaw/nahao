/**
 * class logic
 * @author yanrui@tizi.com
 */
define(function (require, exports) {

    exports.bind_everything = function () {

        //class edit modal
        $(".btn_class_edit").on("click",function(){
            var class_id = $(this).data("class_id");
            var is_last = $(this).data("is_last");
            var round_id = $("#class_list_round_id").data("round_id");
            var tds = $(this).parent().parent().children();
//            console.log(tds);
//            $(tds[3]).text();
//            $(tds[4]).text();
            $("#class_begin_time").val($(tds[4]).text());
            $("#class_end_time").val($(tds[5]).text());
            $("#modal_class_edit").modal();
            //class update
            $("#btn_class_submit").on("click",function(){
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
                    'is_last' : is_last,
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
    }

});