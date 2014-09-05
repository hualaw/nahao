define(function(require,exports){
    exports.employment = function(){
        $('.show').click(function(){
            $.ajax({
                url:'/employment/is_open',
                type:'post',
                data:'is_open='+$(this).data('is_open')+'&id='+$(this).data('id'),
                success:function(msg)
                {
                    alert(msg.msg);
                    location.reload();
                }
            })
        })

        $('#create').click(function(){
            var title= $.trim(CKEDITOR.instances.title.getData());
            var desc= $.trim(CKEDITOR.instances.desc.getData());
            var requirement= $.trim(CKEDITOR.instances.requirement.getData());
            var seq= $.trim($('#seq').val());

            var data={
                'title':title,
                'desc':desc,
                'requirement':requirement,
                'seq':seq
            }

            $.ajax({
                url:'/employment/check_employment',
                data:data,
                type:'post',
                success:function(msg)
                {
                    if(msg.status=='error')
                    {
                        alert(msg.msg);
                        return false;
                    }
                    if(msg.status=='ok')
                    {
                        alert(msg.msg);
                        location.href=msg.redirect;
                    }
                }
            })

        })

        $('#edit').click(function(){
            var title= $.trim(CKEDITOR.instances.title.getData());
            var desc= $.trim(CKEDITOR.instances.desc.getData());
            var requirement= $.trim(CKEDITOR.instances.requirement.getData());
            var seq= $.trim($('#seq').val());
            var id= $.trim($('#modify_id').val());
            var innate= $.trim($('#innate').val());

            var data={
                'title':title,
                'desc':desc,
                'requirement':requirement,
                'seq':seq,
                'id':id,
                'innate':innate
            }

            $.ajax({
                url:'/employment/check_edit_employment',
                data:data,
                type:'post',
                success:function(msg)
                {
                    if(msg.status=='error')
                    {
                        alert(msg.msg);
                        return false;
                    }
                    if(msg.status=='ok')
                    {
                        alert(msg.msg);
                        location.href=msg.redirect;
                    }
                }
            })
        })
    }
    exports.load_ckeditor = function ()
    {
        CKEDITOR.replace('title',{ toolbar:'Basic'});
        CKEDITOR.replace('requirement',{ toolbar:'Basic'});
        CKEDITOR.replace('desc',{ toolbar:'Basic'});
    }
})