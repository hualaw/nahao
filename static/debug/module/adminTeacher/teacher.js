/**
 * teacher
 * @author shangshikai@nahao.com
 */
define(function(require,exports){
    exports.teacher = function(){
        $('#teacher_datetimepicker1').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
        $('#teacher_datetimepicker2').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
        $('#close_account').click(function(){
           var arr_v = new Array();
            $("input[type='checkbox']:checked").each(function(){
                arr_v.push($(this).val());
            });
           // alert(arr_v);
            $.ajax({
                type:"post",
                url:"/teacher/close_account",
                data:"arr="+arr_v,
                success:function(msg){
                   if(msg==true)
                    {
                        location=location;
                    }
                }
            })
        })

        $('#open_account').click(function(){
            var arr_v = new Array();
            $("input[type='checkbox']:checked").each(function(){
                arr_v.push($(this).val());
            });
            // alert(arr_v);
            $.ajax({
                type:"post",
                url:"/teacher/open_account",
                data:"arr="+arr_v,
                success:function(msg){
                    if(msg==true)
                    {
                        location=location;
                    }
                }
            })
        })
            $("#all").click(function(){
                if($("#all").attr("checked"))
                {
                    $(".all").attr("checked","checked");
                }
                else
                {
                    $(".all").removeAttr("checked");
                }
            })

            $('#nickname').blur(function(){
                if($.trim($('#nickname').val())=='')
                {
                    $('#span_nickname').show().css('color','red').html('昵称不能为空');
                }
                else if($('#nickname').val().length<2)
                {
                    $('#span_nickname').show().css('color','red').html('昵称不能少于2个字');
                }
                else if($('#nickname').val().length>15)
                {
                    $('#span_nickname').show().css('color','red').html('昵称不能大于15个字');
                }
                else
                {
                    $.ajax({
                        type:"post",
                        url:"/teacher/nickname",
                        data:"nickname="+$('#nickname').val(),
                        success:function(msg){
                            if(msg>0)
                            {
                                $('#span_nickname').show().css('color','red').html('昵称已存在');
                            }
                            else
                            {
                                $('#span_nickname').hide();
                            }
                        }
                    })
                }
            })

            $('#password').blur(function(){
                if($.trim($('#password').val())=='')
                {
                    $('#span_password').show().css('color','red').html('密码不能为空');
                }
                else if($('#password').val().length<6)
                {
                    $('#span_password').show().css('color','red').html('密码不能少于6个字');
                }
                else if($('#password').val().length>20)
                {
                    $('#span_password').show().css('color','red').html('密码不能大于20个字');
                }
                else
                {
                    $('#span_password').hide();
                }
            })

            $('#realname').blur(function(){
                if($.trim($('#realname').val())=='')
                {
                    $('#span_realname').show().css('color','red').html('真实姓名不能为空');
                }
                else if($('#realname').val().length<2)
                {
                    $('#span_realname').show().css('color','red').html('真实姓名不能少于2个字');
                }
                else if($('#realname').val().length>15)
                {
                    $('#span_realname').show().css('color','red').html('真实姓名不能大于15个字');
                }
                else
                {
                    $('#span_realname').hide();
                }
            })

            $('#school').blur(function(){
                if($.trim($('#school').val())=='')
                {
                    $('#span_school').show().css('color','red').html('学校不能为空');
                }
                else
                {
                    $('#span_school').hide();
                }
            })
            $('#basic_reward').blur(function(){
                if($.trim($('#basic_reward').val())=='')
                {
                    $('#span_basic_reward').show().css('color','red').html('课酬不能为空');
                }
                else if(isNaN($('#basic_reward').val()))
                {
                    $('#span_basic_reward').show().css('color','red').html('必须是一个数字');
                }
                else
                {
                    $('#span_basic_reward').hide();
                }
            })

            $.ajax({
                type:"post",
                url:"/teacher/city",
                data:"province="+$('#province').val(),
                dataType:"json",
                success:function(msg){
                var city=eval(msg);
                    $.each(city,function(index,d){
                        $('#city').append("<option value="+d.id+">"+d.name+"</option>");
                   })
                    $.ajax({
                        type:"post",
                        url:"/teacher/area",
                        data:"city="+$('#city').val(),
                        dataType:"json",
                        success:function(msg){
                            if(msg=="")
                            {
                                $('#area').hide();
                            }
                            var area=eval(msg);
                            $.each(area,function(index,d){
                                $('#area').show().append("<option value="+d.id+">"+d.name+"</option>");
                            })
                        }
                    })
                }
            })

        $('#province').change(function(){
            $.ajax({
                type:"post",
                url:"/teacher/city",
                data:"province="+$('#province').val(),
                dataType:"json",
                success:function(msg){
                    if(msg=="")
                    {
                        $('#city').hide();
                        $('#area').hide();
                    }
                    $('#city').empty();
                    var city=eval(msg);
                    $.each(city,function(index,d){
                        $('#city').show().append("<option value="+d.id+">"+d.name+"</option>");
                    })
                    $.ajax({
                        type:"post",
                        url:"/teacher/area",
                        data:"city="+$('#city').val(),
                        dataType:"json",
                        success:function(msg){
                            if(msg=="")
                            {
                               $('#area').hide();
                            }
                            $('#area').empty();
                            var area=eval(msg);
                            $.each(area,function(index,d){
                                $('#area').show().append("<option value="+d.id+">"+d.name+"</option>");
                            })
                        }
                    })
                }
            })

        })

        $('#city').change(function(){
            $.ajax({
                type:"post",
                url:"/teacher/area",
                data:"city="+$('#city').val(),
                dataType:"json",
                success:function(msg){
                    $('#area').empty();
                    var area=eval(msg);
                    $.each(area,function(index,d){
                        $('#area').append("<option value="+d.id+">"+d.name+"</option>");
                    })
                }
            })
        })

        $('#phone').blur(function(){
            if($.trim($('#phone').val())=="")
            {
                $('#span_phone').show().css('color','red').html("电话不能为空");
            }
            else
            {
                $.ajax({
                    type:"post",
                    url:"/teacher/check_phone",
                    data:"phone="+$('#phone').val(),
                    success:function(msg){
                        if(msg==1)
                        {
                            $('#span_phone').show().css('color','red').html("这不是一个合法的手机号");
                        }
                        else if(msg==2)
                        {
                            $('#span_phone').show().css('color','red').html("手机号已存在");
                        }
                        else
                        {
                            $('#span_phone').hide();
                        }
                    }
                })
            }
        })


        $('#email').blur(function(){
            if($.trim($('#email').val())=="")
            {
                $('#span_email').show().css('color','red').html("邮箱不能为空");
            }
            else
            {
                $.ajax({
                    type:"post",
                    url:"/teacher/check_email",
                    data:"email="+$('#email').val(),
                    success:function(msg){
                        if(msg=="no")
                        {
                            $('#span_email').show().css('color','red').html("这不是一个合法的邮箱");
                        }
                        else if(msg>0)
                        {
                            $('#span_email').show().css('color','red').html("邮箱已存在");
                        }
                        else
                        {
                            $('#span_email').hide();
                        }
                    }
                })
            }
        })

    }
})