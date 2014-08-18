define(function(require,exports){
    exports.topLogin=function(){
        $('#top_login').click(function(){
            $.ajax({
                type:'post',
                url:'/login/submit',
                data:'username='+ $.trim($('#top_username').val())+'&password=' + $.trim($('#top_password').val()),
                success:function(msg)
                {
                    location.reload();
                }
            })
        })
    }
})