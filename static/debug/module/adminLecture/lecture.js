/**
 * lecture
 * @author shangshikai@nahao.com
 */
define(function(require,exports){
    exports.lecture = function(){
        $('#lecture_datetimepicker1').datetimepicker({
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
        $('#lecture_datetimepicker2').datetimepicker({
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

        $('#keep').click(function(){
            $.ajax({
                type:"post",
                url:"/lecture/insert_note",
                data:"lecture_id="+$('#lecture_id').val()+"&lectrue_notes="+$("#lectrue_notes").val(),
                success:function(msg){
                    if(msg==1)
                    {
                        location.reload();
                    }
                    else
                    {
                        alert('备注信息不能为空');
                    }
                }
            })
        })


        $('#pass').click(function(){
            $.ajax({
                type:"post",
                url:"/lecture/pass_lecture",
                data:"lecture_id="+$('#lecture_id').val()+"&user_id="+$('#user_id').val()+"&gender="+$('#gender').val()+"&realname="+$('#tea_name').html()+"&age="+$('#age').val()+"&school="+$('#school').val()+"&province="+$('#province').val()+"&city="+$('#city').val()+"&area="+$('#area').val()+"&stage="+$('#stage').val()+"&teacher_age="+$('#teach_years').val()+"&teacher_intro="+$('#resume').val()+"&title="+$('#title').val()+"&subject="+$('#subject').val()+"&basic_reward="+$('#basic_reward').val(),
                success:function(msg){
                    if(msg==1)
                    {
                        $('.curr_status').html('审核通过');                                                                location.reload();
                    }
                    if(msg==2)
                    {
                        alert('课酬填写错误');
                    }
                }
            })
        })

        $('#indeterminate').click(function(){
            $.ajax({
                type:"post",
                url:"/lecture/indeterminate_lecture",
                data:"lecture_id="+$('#lecture_id').val(),
                success:function(msg){
                    if(msg==1)
                    {
                        $('.curr_status').html('待定');
                    }
                }
            })
        })

        $('#nopass').click(function(){
            $.ajax({
                type:"post",
                url:"/lecture/nopass_lecture",
                data:"lecture_id="+$('#lecture_id').val(),
                success:function(msg){
                    if(msg==1)
                    {
                        $('.curr_status').html('审核不通过');
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
        $('#agree').click(function(){
            $.ajax({
                type:"post",
                url:"/lecture/agree_lecture",
                data:"lecture_id="+$('#lecture_id').val()+'&start_time='+$('#start_time').val()+'&end_time='+$('#end_time').val()+'&subject='+$('#subject').val()+'&course='+$('#course').val(),
                success:function(msg){
                    if(msg>0)
                    {
                        $('.curr_status').html('允许试讲');
                        location.href='/lecture/lecture_class_list';
                    }
                }
            })
        })

        $('#disagree').click(function(){
            $.ajax({
                type:"post",
                url:"/lecture/disagree_lecture",
                data:"lecture_id="+$('#lecture_id').val(),
                success:function(msg){
                    if(msg==1)
                    {
                        $('.curr_status').html('拒绝试讲');
                        $('#courseware').hide();
                    }
                }
            })
        })

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
    }
})