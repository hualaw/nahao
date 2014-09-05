define(function(require,exports){
    exports.identifying = function(){
        $("#phone").blur(function(){
            $.ajax({
                type:'post',
                url:'identifying/eny_phone',
                data:'phone='+$('#phone').val(),
                success:function(msg){
                    if(msg==0)
                    {
                        alert("电话不对");
                        return false;
                    }
                }
            })
        })
    }
})