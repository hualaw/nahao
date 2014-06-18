/**
 * order
 * @author shangshikai@nahao.com
 */
define(function(require,exports){
    exports.order = function(){
        $("#button").click(function(){
        $.ajax({
            type:"post",
            url:"/order/refund",
            data:"student_id="+$('#student_id').val()+"&order_id="+$("#order_id").val(),
            success:function(msg){
                if(msg==1)
                {
                    $("#button").hide();
                    $("#refund").html("退款不通过");
                }
            }
        })
    })


        $("#agr_button").click(function(){
            $.ajax({
                type:"post",
                url:"/order/suc_refund",
                data:"student_id="+$('#student_id').val()+"&order_id="+$("#order_id").val(),
                success:function(msg){
                    if(msg==1)
                    {
                        location=location;
                    }
                }
            })
        })

        $("#suc_button").click(function(){
            $.ajax({
                type:"post",
                url:"/order/ok_refund",
                data:"student_id="+$('#student_id').val()+"&order_id="+$("#order_id").val(),
                success:function(msg){
                    if(msg==1)
                    {
                        location=location;
                    }
                }
            })
        })


        $('#show').click(function(){
            $.ajax({
                type:"post",
                url:"/order/show_phone",
                data:"uid="+$("#student_id").val(),
                success:function(msg){
                    $("#show").val(msg);
                }
            })
        })

        $('#show').click(function(){
            var p=$("#mask").val();
            $("#show").val(p);
        })


        $("#memory").click(function(){
            $.ajax({
                type:"post",
                url:"/order/insert_note",
                data:"order_id="+$("#order_id").val()+"&note="+$("#note_content").val(),
                success:function(msg){
                    if(msg==1)
                    {
                        location=location;
                    }
                    else
                    {
                        alert("备注信息不能为空");
                    }
                }
            })
        })



        $('#order_datetimepicker1').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
//        pickDate: true,
//        pickTime: true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
        $('#order_datetimepicker2').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
//        pickDate: true,
//        pickTime: true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
        $('#order_datetimepicker3').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
//        pickDate: true,
//        pickTime: true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
        $('#order_datetimepicker4').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
//        pickDate: true,
//        pickTime: true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
//alert("d");

    }
});