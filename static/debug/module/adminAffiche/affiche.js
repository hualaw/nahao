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
            $('#edit_content').val($(this).data('content'));
            $('#affiche_id').val($(this).data('affiche_id'));
        })
        $('#edit').click(function(){
            $.ajax({
                type:'post',
                url:'/affiche/edit_content',
                data:'id='+$('#affiche_id').val()+"&content="+$('#edit_content').val(),
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
            $('#insert_content').val("");
            $("#insert_Modal").modal();
        })
        $('#insert').click(function(){
            $.ajax({
                type:'post',
                url:'/affiche/insert_affiche',
                data:'content='+$('#insert_content').val()+'&role=1'+'&round_id='+$('#round_id').val(),
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
})