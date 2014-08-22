define(function(require,exports){
    exports.focus_photo = function(){
        $('.modify').click(function(){
            $('#myModal').modal();
            $('#round_id').val($(this).data('round_id'));
            $('#is_round').val($(this).data('round_id'));
            $('#color').val($(this).data('color'));
            $('#img_src').val($(this).data('img_src'));
            $('#photo_id').val($(this).data('photo_id'));
        })

        $('#edit').click(function(){
            if(isNaN($.trim($('#round_id').val())) || $.trim($('#round_id').val())=='')
            {
                alert('轮ID不是一个数字或为空');
                return false;
            }
            if($.trim($('#color').val())=='')
            {
                alert('颜色不能为空');
                return false;
            }

            $.ajax({
                type:'post',
                url:'/focus_photo/check_round_id',
                data:'round_id='+$.trim($('#round_id').val())+'&is_round='+$('#is_round').val(),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        $.ajax({
                            type:'post',
                            url:'/focus_photo/modify',
                            data:'photo_id='+ $.trim($('#photo_id').val())+'&round_id='+$.trim($('#round_id').val())+'&color='+ $.trim($('#color').val()),
                            success:function(msg)
                            {
                                if(msg>0)
                                {
                                    location.reload();
                                }
                                else
                                {
                                    alert('未作修改');
                                    location.reload();
                                }
                            }
                        })
                    }
                    else
                    {
                        alert('轮ID已经存在');
                        return false;
                    }
                }
            })
        })

        $('.show').click(function(){
            $.ajax({
                type:'post',
                url:'/focus_photo/is_show',
                data:'id='+$(this).data('id')+'&is_show='+$(this).data('is_show'),
                success:function(msg)
                {
                    location.reload();
                }
            })
        })

        $('.noshow').click(function(){
            $.ajax({
                type:'post',
                url:'/focus_photo/is_show',
                data:'id='+$(this).data('id')+'&is_show='+$(this).data('is_show'),
                success:function(msg)
                {
                    location.reload();
                }
            })
        })

        $('#add').click(function(){
            if(isNaN($.trim($('#round_id').val())) || $.trim($('#round_id').val())=='')
            {
                alert('轮ID不是一个数字或为空');
                return false;
            }
            if($.trim($('#color').val())=='')
            {
                alert('颜色不能为空');
                return false;
            }
        })

        $('#round_id').blur(function(){
            $.ajax({
                type:'post',
                url:'/focus_photo/check_round_id',
                data:'round_id='+$.trim($('#round_id').val())+'&is_round='+$('#is_round').val(),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        return true;
                    }
                    else
                    {
                        alert('轮ID已经存在');
                        return false;
                    }
                }
            })
        })

        $('#add_sort').blur(function(){
            if(isNaN($.trim($('#add_sort').val())) || $.trim($('#add_sort').val())=="")
            {
                alert('不是一个数字或者为空');
                return false;
            }

            $.ajax({
                url:'/focus_photo/is_sort',
                type:'post',
                data:'sort='+$.trim($('#add_sort').val()),
                success:function(msg)
                {
                    if(msg>0)
                    {
                        alert('顺序已存在');
                    }
                }
            })
        })

        $('.edit_sort').click(function(){
            $('#edit_id').val($(this).data('edit_id'));
            $('#sort').val($(this).data('sort'));
            $('#hidden_sort').val($(this).data('sort'));
            $('#edit_sort').modal();
        })

        $('#modify').click(function(){
            if($.trim($('#sort').val())==$('#hidden_sort').val())
            {
                alert('未作修改');
                return false;
            }
            if(isNaN($.trim($('#sort').val())) || $.trim($('#sort').val())=="")
            {
                alert('不是一个数字或者为空');
                return false;
            }
            $.ajax({
                url:'/focus_photo/is_sort',
                type:'post',
                data:'sort='+$.trim($('#sort').val()),
                success:function(msg)
                {
                    if(msg>0)
                    {
                        alert('顺序已存在');
                        return false;
                    }
                    else
                    {
                        $.ajax({
                            url:'/focus_photo/modify',
                            type:'post',
                            data:'photo_id='+$('#edit_id').val()+'&sort='+ $.trim($('#sort').val()),
                            success:function(msg)
                            {
                                if(msg==1)
                                {
                                    alert('修改成功');
                                    location.reload();
                                }
                            }
                        })
                    }
                }
            })
        })
    }
})