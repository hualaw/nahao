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
                        location.reload();
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
                        location.reload();
                    }
                }
            })
        })


        $('#show').focus(function(){
            $.ajax({
                type:"post",
                url:"/order/show_phone",
                data:"uid="+$("#student_id").val(),
                success:function(msg){
                    $("#show").val(msg);
                }
            })
        })

        $('#show').blur(function(){
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
                        location.reload();
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

        $('#modify_price').click(function(){
            $('#myModal').modal();
            $('#price_modify').val($('#spend').html());
        })

        $('#ok_modify').click(function(){
            //alert($('#spend').html())
            if($('#price_modify').val()<0 || $('#price_modify').val()==0 || $('#price_modify').val()=="" || isNaN($('#price_modify').val()))
            {
                alert('输入的价格不合法');
                return false;
            }
            $.ajax({
                type:"post",
                url:"/order/modify_price",
                data:"modify_price="+$("#price_modify").val()+"&order_id="+$('#student_order_id').html()+"&spend="+$('#spend').html(),
                success:function(msg){
                    //alert(msg);
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })
        $("#pay_money").click(function(){
            $.ajax({
                type:"post",
                url:"/order/pay_money",
                data:"order_id="+$('#student_order_id').html()+"&user_id="+$('#student_id').val()+"&round_id="+$("#round_id").html(),
                success:function(msg){
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })
    }
});