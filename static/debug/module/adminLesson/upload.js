define(function(require,exports){
    require("flashUploader");

    //upload courseware
    exports.upload = function(){
        $('.btn_upload_pdf').on("click",function(){
            $("#pdf_upload_area").hide();
            $("#token_load_area").show();
            var url = '/lesson/token';
            console.log(123);
            var lesson_id = $(this).data('lesson_id');
            //get upload token
            $.get(url,function (response){
                if(response && response.token !='' ){
                    $("#token_load_area").hide();
                    $("#pdf_upload_area").show();
                    //upload to meeting system
                    $('#lesson_pdf_upload').uploadify({
                        'formData' : response,
                        'swf'      : 'http://admin.nahaodev.com/static/debug/lib/uploadify/2.2/uploadify.swf',
                        'uploader' : 'http://classroom.oa.tizi.com/api/files/', //post to meeting system
                        'multi'    : true,
                        'fileObjName' : 'fileobj',
                        onUploadSuccess: function(file, data, response) {
                            var data = jQuery.parseJSON(data);
                            if(data.id && data.id>0){
                                var url = '/lesson/add_courseware';
                                var data = {
                                    'lesson_id' : lesson_id,
                                    'courseware_id' : data.id
                                };
                                //add courseware_id to lesson
                                $.post(url, data, function(response){
                                    if(response){
                                        alert(response.msg);
                                        window.location.reload;
                                    }
                                });
                            }
                        }
                    });
                }
            })
            $('#lesson_upload_modal').modal();
        });
    }

});