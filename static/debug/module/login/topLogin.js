define(function(require,exports){
    exports.topLogin=function(){
        // var _Form=$(".topLoginForm").Validform({
        //     // 自定义tips在输入框上面显示
        //     tiptype:function(msg,o,cssctl){
        //         var objtip=$("#span_warning");
        //         cssctl(objtip,o.type);
        //         objtip.text(msg);
        //     },
        //     showAllError:false,
        //     ajaxPost:true,
        //     beforeSubmit:function(){
                
        //     },
        //     callback:function(msg){
        //         if(msg.status=='ok'){
        //             location.reload();
        //         }else{
        //             alert('帐号或者密码错误');
        //         }
        //     }
        // });

        // _Form.addRule([{
        //          ele:"#top_username",
        //          ignore:"ignore",
        //          datatype: "e",
        //          nullmsg: "请输入邮箱",
        //          errormsg: "请输入正确的邮箱地址"
        //     },
        //     {   
        //          ele:"#top_password",
        //          ignore:"ignore",
        //          datatype: "*6-20",
        //          nullmsg: "请输入密码",
        //          errormsg: "密码长度只能在6-20位字符之间"
        //     }
        // ]);
        

        $('#top_login').click(function(){
            $.ajax({
                type:'post',
                url:'/login/submit',
                data:'username='+ $.trim($('#top_username').val())+'&password=' + $.trim($('#top_password').val()),
                success:function(msg)
                {
                    if(msg.status=='ok')
                    {
                        location.reload();
                    }
                    else
                    {
                        alert('帐号或者密码错误');
                    }
                }
            })
        })
    }
})