define(function(require,exports){
    require("flashUploader");

    //upload courseware
    exports.upload = function(){
        $('.btn_upload_pdf').on("click",function(){
            $("#pdf_upload_area").hide();
            $("#token_load_area").show();
            var url = '/lesson/token';
            var lesson_id = $(this).data('lesson_id');
            //get upload token
            $.get(url,function (response){
                if(response){
                    if(response.token !='' ){
                        $("#token_load_area").hide();
                        $("#pdf_upload_area").show();

                        $('#lesson_pdf_upload').uploadify({});
                        //upload to meeting system
                    $('#lesson_pdf_upload').uploadify({
                        'formData' : response,
                        'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
                        'uploader' : _meeting_url+'api/files/', //post to meeting system
                        'multi'    : true,
                        'fileObjName' : 'fileobj',
                        'fileTypeExts' : '*.pdf',
                        'fileSizeLimit' : '10MB',
                        onUploadSuccess: function(file, data, response) {
                            var data = jQuery.parseJSON(data);

                            if(data.id && data.id>0){
//                                console.log(data);
                                var url = '/lesson/add_courseware';
                                var data = {
                                    'lesson_id' : lesson_id,
                                    'courseware_id' : data.id,
                                    'create_time' : data.created_at,
                                    'filename' : data.filename,
                                    'filesize' : data.filesize,
                                    'filetype' : data.filetype
                                };
                                //add courseware_id to lesson
                                $.post(url, data, function(response){
                                    if(response){
                                        alert(response.msg);
                                        window.location.reload();
                                    }
                                });
                            }
                        }
                    });
                    }else{
                        $("#token_display_area").html('token读取失败请重试');
                    }
                }
            })
            $('#lesson_upload_modal').modal();
        });
    }

});