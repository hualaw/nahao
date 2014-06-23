define(function(require,exports){
    exports.feedback = function(){
        $('.show_feedback').click(function(){
            $.ajax({
                type:'post',
                url:'/feedback/show_hide_feedback',
                data:'id='+$(this).data('feedback_id')+"&a="+$(this).data('a'),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })

        $('.hide_feedback').click(function(){
            $.ajax({
                type:'post',
                url:'/feedback/show_hide_feedback',
                data:'id='+$(this).data('feedback_id')+"&a="+$(this).data('a'),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })

        $('#start_time').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
        $('#end_time').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
    }
})