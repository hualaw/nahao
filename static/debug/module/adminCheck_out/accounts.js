define(function(require,exports){
    exports.accounts = function(){
        $('#settle_datetimepicker1').datetimepicker({
            format: "yyyy-MM",
            language: 'cn',
            autoclose : true,
            inputMask: true,
            startView:3,
            minView:3
        })

        $("#ok_checkout").click(function(){
            $.ajax({
                type:'post',
                data:'check_id='+$('#checkout_id').val(),
                url:'/check_out/ok_checkout',
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location=location;
                    }
                }
            })
        })

        $("#ok_pay").click(function(){
            $.ajax({
                type:'post',
                data:'user_id='+$('#user_id').val()+'&check_id='+$('#checkout_id').val()+"&net_income="+$('#net_income').val(),
                url:'/check_out/ok_pay',
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location=location;
                    }
                }
            })
        })

        $('#show').focus(function(){
            $.ajax({
                type:"post",
                url:"/order/show_phone",
                data:"uid="+$("#user_id").val(),
                success:function(msg){
                    $("#show").val(msg);
                }
            })
        })

        $('#show').blur(function(){
            var p=$("#mask").val();
            $("#show").val(p);
        })
    }
})