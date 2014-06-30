define(function(require,exports){
    exports.affiche = function(){
        $('#start_time').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })
        $('#end_time').datetimepicker({
            format: "yyyy-MM-dd hh:ii",
            language: 'cn',
            autoclose : true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        })

        $('.edit').click(function(){
            $('#myModal').modal();
            CKEDITOR.instances.edit_content.setData($(this).data('content'));
            $('#affiche_id').val($(this).data('affiche_id'));
        })
        $('#edit').click(function(){
            $.ajax({
                type:'post',
                url:'/affiche/edit_content',
                data:'id='+$('#affiche_id').val()+"&content="+CKEDITOR.instances.edit_content.getData(),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })
        $('.pass').click(function(){
            $.ajax({
                type:'post',
                url:'/affiche/pass',
                data:'id='+$(this).data('affiche_id'),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })
        $('.nopass').click(function(){
            $.ajax({
                type:'post',
                url:'/affiche/nopass',
                data:'id='+$(this).data('affiche_id'),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })
        $('.top').click(function(){
            $.ajax({
                type:'post',
                url:'/affiche/top',
                data:'id='+$(this).data('affiche_id'),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })
        $('.notop').click(function(){
            $.ajax({
                type:'post',
                url:'/affiche/notop',
                data:'id='+$(this).data('affiche_id'),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })

        $('#insert_affiche').click(function(){
            CKEDITOR.instances.insert_content.setData("");
            $("#insert_Modal").modal();
        })
        $('#insert').click(function(){
            $.ajax({
                type:'post',
                url:'/affiche/insert_affiche',
                data:'content='+CKEDITOR.instances.insert_content.getData()
            +'&role=1'+'&round_id='+$('#round_id').val(),
                success:function(msg)
                {
                    if(msg==1)
                    {
                        location.reload();
                    }
                }
            })
        })
    }
    exports.load_ckeditor = function ()
    {
        CKEDITOR.replace('insert_content',{ toolbar:'Basic'});
        CKEDITOR.replace('edit_content',{ toolbar:'Basic'});
    }
})