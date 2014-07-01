define(function(require,exports){
    exports.testround = function(){
        $('#lecture_datetimepicker1').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
//        pickDate: true,
//        pickTime: true,
            hourStep: 1,
            minuteStep: 1,
            secondStep: 30,
            inputMask: true
        })
        $('#lecture_datetimepicker2').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
//        pickDate: true,
//        pickTime: true,
            hourStep: 1,
            minuteStep: 1,
            secondStep: 30,
            inputMask: true
        })
        $('#lecture_datetimepicker3').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
//        pickDate: true,
//        pickTime: true,
            hourStep: 1,
            minuteStep: 1,
            secondStep: 30,
            inputMask: true
        })
        $('#lecture_datetimepicker4').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
//        pickDate: true,
//        pickTime: true,
            hourStep: 1,
            minuteStep: 1,
            secondStep: 30,
            inputMask: true
        })

        $('#success').click(function(){
            $.ajax({
                type:'post',
                url:'/testround/edit',
                data:'round_id='+$('#round_id').val()+'&sale_status='+$('#sale_status').val()+'&sell_begin_time='+$('.sell_begin_time').val()+'&sell_end_time='+$('.sell_end_time').val()+'&teach_status='+$('#teach_status').val()+'&start_time='+$('.start_time').val()+'&end_time='+$('.end_time').val(),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })
    }
})