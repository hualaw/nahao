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
                        location=location;
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
                data:"lecture_id="+$('#lecture_id').val()+"&user_id="+$('#user_id').val()+"&gender="+$('#gender').val()+"&realname="+$('#tea_name').val()+"&age="+$('#age').val()+"&school="+$('#school').val()+"&province="+$('#province').val()+"&city="+$('#city').val()+"&area="+$('#area').val()+"&stage="+$('#stage').val()+"&teacher_age="+$('#teach_years').val()+"&teacher_intro="+$('#resume').val()+"&titile_auth="+$('#title').val()+"&email="+$('#email_lec').html()+"&phone="+$('#show').val(),
                success:function(msg){
                    if(msg==1)
                    {
                        $('#curr_status').html('审核通过');                                                                 location=location;
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
                        $('#curr_status').html('待定');
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
                        $('#curr_status').html('审核不通过');
                    }
                }
            })
        })

        }
    })