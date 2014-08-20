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
            //alert(arr_v);
            $.ajax({
                type:"post",
                url:"/teacher/close_account",
                data:"arr="+arr_v,
                success:function(msg){
                   if(msg==true)
                    {
                        location.reload();
                    }
                }
            })
        })

        $('#open_account').click(function(){
            var arr_v = new Array();
            $("input[type='checkbox']:checked").each(function(){
                arr_v.push($(this).val());
            });
            //alert(arr_v);
            $.ajax({
                type:"post",
                url:"/teacher/open_account",
                data:"arr="+arr_v,
                success:function(msg){
                    if(msg==true)
                    {
                        location.reload();
                    }
                    if(msg=="no")
                    {
                        alert("选择的账户里有昵称已被占用");
                        location.reload();
                        return false;
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
                else
                {
                    $.ajax({
                        type:"post",
                        url:"/teacher/nickname",
                        data:"nickname="+$('#nickname').val()+"&user_id="+$("#user_teacher_id").val(),
                        success:function(msg){
                            if(msg=="yes")
                            {
                                //alert(msg);
                                $('#span_nickname').show().css('color','red').html('昵称已存在');
                            }
                            else if(msg==2)
                            {
                                $('#span_nickname').show().css('color','red').html('不符合规则,至少两个汉字或四个字符，最多8个汉字或16个字符');
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
                else
                {
                    $.ajax({
                        type:"post",
                        url:"/teacher/check_realname_length",
                        data:"realname="+$('#realname').val(),
                        success:function(msg){
                            if(msg==2)
                            {
                                $('#span_realname').show().css('color','red').html('不符合规则,至少两个汉字或四个字符，最多8个汉字或16个字符');
                            }
                            else
                            {
                                $('#span_realname').hide();
                            }
                        }
                    })
                }

            })

            $("#sum").click(function(){
                var teacher_intro=CKEDITOR.instances.teacher_intro.getData();
                if(teacher_intro.length>=450)
                {
                    alert('个人简介过长，不建议超过450字');
                    return false;
                }
            })
            $('#teacher_signature').blur(function(){
                if($.trim($('#teacher_signature').val())=='')
                {
                    $('#span_teacher_signature').show().css('color','red').html('不能为空');
                }
                else
                {
                    $('#span_teacher_signature').hide();
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
                else if($('#basic_reward').val()<0)
                {
                    $('#span_basic_reward').show().css('color','red').html('不能小于0');
                }
                else
                {
                    $('#span_basic_reward').hide();
                }
            })

        $('#age').blur(function(){
            if($.trim($('#age').val())=='')
            {
                $('#span_age').show().css('color','red').html('年龄不能为空');
            }
            else if(isNaN($('#age').val()))
            {
                $('#span_age').show().css('color','red').html('必须是一个数字');
            }
            else if($('#age').val()<20)
            {
                $('#span_age').show().css('color','red').html('不能小于20');
            }
            else if($('#age').val()>100)
            {
                $('#span_age').show().css('color','red').html('不能大于100');
            }
            else
            {
                $('#span_age').hide();
            }
        })

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

                            if($('#area').val()!=null)
                            {
                                $.ajax({
                                    type:"post",
                                    url:"/teacher/school",
                                    data:"school_id="+$('#area').val(),
                                    dataType:"json",
                                    success:function(msg){
                                        // console.log(msg);
                                        $('#school').empty();
                                        var school=eval(msg);
                                        $('#school').append("<option value=0>请选择学校</option>");
                                        $.each(school,function(index,d){
                                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                        })
                                    }
                                })
                            }

                            else if($('#city').val()!=null)
                            {
                                $.ajax({
                                    type:"post",
                                    url:"/teacher/school",
                                    data:"school_id="+$('#city').val(),
                                    dataType:"json",
                                    success:function(msg){
                                        // console.log(msg);
                                        $('#school').empty();
                                        var school=eval(msg);
                                        $('#school').append("<option value=0>请选择学校</option>");
                                        $.each(school,function(index,d){
                                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                        })
                                    }
                                })
                            }
                            else if($('#province').val()!=null)
                            {
                                $.ajax({
                                    type:"post",
                                    url:"/teacher/school",
                                    data:"school_id="+$('#province').val(),
                                    dataType:"json",
                                    success:function(msg){
                                        // console.log(msg);
                                        $('#school').empty();
                                        var school=eval(msg);
                                        $('#school').append("<option value=0>请选择学校</option>");
                                        $.each(school,function(index,d){
                                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                        })
                                    }
                                })
                            }

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

                            if($('#area').val()!=null)
                            {
                                $.ajax({
                                    type:"post",
                                    url:"/teacher/school",
                                    data:"school_id="+$('#area').val(),
                                    dataType:"json",
                                    success:function(msg){
                                        // console.log(msg);
                                        $('#school').empty();
                                        var school=eval(msg);
                                        $('#school').append("<option value=0>请选择学校</option>");
                                        $.each(school,function(index,d){
                                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                        })
                                    }
                                })
                            }

                            else if($('#city').val()!=null)
                            {
                                $.ajax({
                                    type:"post",
                                    url:"/teacher/school",
                                    data:"school_id="+$('#city').val(),
                                    dataType:"json",
                                    success:function(msg){
                                        // console.log(msg);
                                        $('#school').empty();
                                        var school=eval(msg);
                                        $('#school').append("<option value=0>请选择学校</option>");
                                        $.each(school,function(index,d){
                                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                        })
                                    }
                                })
                            }
                            else if($('#province').val()!=null)
                            {
                                $.ajax({
                                    type:"post",
                                    url:"/teacher/school",
                                    data:"school_id="+$('#province').val(),
                                    dataType:"json",
                                    success:function(msg){
                                        // console.log(msg);
                                        $('#school').empty();
                                        var school=eval(msg);
                                        $('#school').append("<option value=0>请选择学校</option>");
                                        $.each(school,function(index,d){
                                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                        })
                                    }
                                })
                            }

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

                    if($('#area').val()!=null)
                    {
                        $.ajax({
                            type:"post",
                            url:"/teacher/school",
                            data:"school_id="+$('#area').val(),
                            dataType:"json",
                            success:function(msg){
                                // console.log(msg);
                                $('#school').empty();
                                var school=eval(msg);
                                $('#school').append("<option value=0>请选择学校</option>");
                                $.each(school,function(index,d){
                                    $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                })
                            }
                        })
                    }

                    else if($('#city').val()!=null)
                    {
                        $.ajax({
                            type:"post",
                            url:"/teacher/school",
                            data:"school_id="+$('#city').val(),
                            dataType:"json",
                            success:function(msg){
                                // console.log(msg);
                                $('#school').empty();
                                var school=eval(msg);
                                $('#school').append("<option value=0>请选择学校</option>");
                                $.each(school,function(index,d){
                                    $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                })
                            }
                        })
                    }
                    else if($('#province').val()!=null)
                    {
                        $.ajax({
                            type:"post",
                            url:"/teacher/school",
                            data:"school_id="+$('#province').val(),
                            dataType:"json",
                            success:function(msg){
                                // console.log(msg);
                                $('#school').empty();
                                var school=eval(msg);
                                $('#school').append("<option value=0>请选择学校</option>");
                                $.each(school,function(index,d){
                                    $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                                })
                            }
                        })
                    }

                }
            })
        })

        $('#area').change(function(){
            if($('#area').val()!=null)
            {
                $.ajax({
                    type:"post",
                    url:"/teacher/school",
                    data:"school_id="+$('#area').val(),
                    dataType:"json",
                    success:function(msg){
                        // console.log(msg);
                        $('#school').empty();
                        $('#school').append("<option value=0>请选择学校</option>");
                        var school=eval(msg);
                        $.each(school,function(index,d){
                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                        })
                    }
                })
            }

            else if($('#city').val()!=null)
            {
                $.ajax({
                    type:"post",
                    url:"/teacher/school",
                    data:"school_id="+$('#city').val(),
                    dataType:"json",
                    success:function(msg){
                        // console.log(msg);
                        $('#school').empty();
                        $('#school').append("<option value=0>请选择学校</option>");
                        var school=eval(msg);
                        $.each(school,function(index,d){
                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                        })
                    }
                })
            }
            else if($('#province').val()!=null)
            {
                $.ajax({
                    type:"post",
                    url:"/teacher/school",
                    data:"school_id="+$('#province').val(),
                    dataType:"json",
                    success:function(msg){
                        // console.log(msg);
                        $('#school').empty();
                        $('#school').append("<option value=0>请选择学校</option>");
                        var school=eval(msg);
                        $.each(school,function(index,d){
                            $('#school').append("<option value="+d.id+">"+d.schoolname+"</option>");
                        })
                    }
                })
            }
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
                        if(msg==3)
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
            var _card = require("module/common/method/bankcard");
            var _idcard = require("module/common/method/idCard");

            $("#id_card").blur(function(){
                if(_idcard.idCard($("#id_card").val())==false&&$("#id_card").val()!="")
                {
                    $("#card_span").show().css('color','red').html("身份证号填写不对");
                }
                else
                {
                    $("#card_span").hide();
                }
            })

            $("#bank_id").blur(function(){
                if(_card.luhmCheck($("#bank_id").val())==false&&$("#bank_id").val()!="")
                {
                    $("#bank_span").show().css('color','red').html("银行卡号填写不对");
                }
                else
                {
                    $("#bank_span").hide();
                }
            })

            $('#sum').click(function(){
                if(_idcard.idCard($("#id_card").val())==false&&$("#id_card").val()!="")
                {
                    alert('身份证号不对');
                    return false;
                }
                if(_card.luhmCheck($("#bank_id").val())==false&&$("#bank_id").val()!="")
                {
                    alert('银行卡号不对');
                    return false;
                }
            })

            if($('#teacher_img').val()!="")
            {
                var img_url_general = _qiniu_url+$('#teacher_img').val();
                var modify_img=$('#teacher_img').val();
                $('#teacher_authimg').val(modify_img);
                $('#img_url_general').attr('src',img_url_general);
            }
            if($('#title_img').val()!="")
            {
                var img_url_general = _qiniu_url+$('#title_img').val();
                var modify_img=$('#title_img').val();
                $('#title_authimg').val(modify_img);
                $('#img_title_general').attr('src',img_url_general);
            }
            if($('#work_img').val()!="")
            {
                var img_url_general = _qiniu_url+$('#work_img').val();
                var modify_img=$('#work_img').val();
                $('#work_authimg').val(modify_img);
                $('#img_work_general').attr('src',img_url_general);
            }
            if($('#avatar_teacher_img').val()!="")
            {
                var img_url_general = _qiniu_url+$('#avatar_teacher_img').val();
                var modify_img=$('#avatar_teacher_img').val();
                $('#teacher_avatar_img').val(modify_img);
                $('#img_avatar').attr('src',img_url_general);
            }

            $('.modify_password').click(function(){
                $('#new_password').val('');
                $('#two_password').val('');
                $('#myModal').modal();
                var id=$(this).attr('id');
                $('#pwd_id').val(id);
            })

            $('#edit_password').click(function(){
                if($('#new_password').val()=='')
                {
                    alert('新密码不能为空');
                    return false;
                }
                if($('#new_password').val().length<6 || $('#new_password').val().length>16)
                {
                    alert('新密码长度输入不能小于6或者大于16');
                    return false;
                }
                if($('#new_password').val()!=$('#two_password').val())
                {
                    alert('两次输入不一致');
                    return false;
                }
                $.ajax({
                    type:"post",
                    url:"/teacher/modify_password",
                    data:"id="+$('#pwd_id').val()+"&password="+$('#new_password').val(),
                    success:function(msg){
                        if(msg>0)
                        {
                            alert('修改密码成功');
                            location.reload();
                        }
                        else
                        {
                            alert('修改密码失败');
                            location.reload();
                        }
                    }
                })
            })

            $('.modify_phone').click(function(){
                $('#new_phone').val('');
                var id=$(this).attr('user_id');
                $('#phone_id').val(id);
                $.ajax({
                    type:'post',
                    url:'teacher/get_phone',
                    data:'id='+id,
                    success:function(msg){
                        $('#new_phone').val(msg);
                        $('#old_phone').val(msg);
                        $('#edit_phone').modal();
                    }
                })
            })

            $('#edit_tel').click(function(){
                if($.trim($('#new_phone').val())== $.trim($('#old_phone').val()))
                {
                    alert('不能修改为原来的手机号');
                    return false;
                }
                var id=$('#phone_id').val();
                $.ajax({
                    type:'post',
                    url:'teacher/check_phone',
                    data:'phone='+ $.trim($('#new_phone').val()),
                    success:function(msg){
                        if(msg==3)
                        {
                            alert('格式不对');
                            return false;
                        }
                        else if(msg==2)
                        {
                            alert('手机号已经存在');
                            return false;
                        }
                        else
                        {
                            $.ajax({
                                type:'post',
                                url:'teacher/modify_phone',
                                data:'phone='+ $.trim($('#new_phone').val())+'&id='+id,
                                success:function(msg){
                                    if(msg>0)
                                    {
                                        alert('修改成功');
                                        location.reload();
                                    }
                                    else
                                    {
                                        alert('修改失败');
                                        location.relod();
                                    }
                                }
                            })
                        }
                    }
                })
            })
    }
    $('#edit_email').blur(function(){
        if($.trim($('#edit_email').val())=="")
        {
            alert('邮箱不能为空');
            return false;
        }
        if($.trim($('#edit_email').val())!= $.trim($('#hide_email').val()))
        {
            $.ajax({
                type:'post',
                url:'/teacher/check_email',
                data:'email='+ $.trim($('#edit_email').val()),
                success:function(msg){
                    if(msg=='no')
                    {
                        alert('这不是一个合法的邮箱');
                        return false;
                    }
                    if(msg>0)
                    {
                        alert('邮箱已存在');
                        return false;
                    }
                }
            })
        }
    })
    exports.load_ckeditor = function ()
    {
        CKEDITOR.replace('teacher_intro',{ toolbar:'Basic'});
    }
})