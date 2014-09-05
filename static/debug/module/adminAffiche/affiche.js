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
            CKEDITOR.instances.edit_content.setData($(this).data('content'));
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
        })
    }
    exports.load_ckeditor = function ()
    {
        CKEDITOR.replace('insert_content',{ toolbar:'Basic'});
        CKEDITOR.replace('edit_content',{ toolbar:'Basic'});
    }
})