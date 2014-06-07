define(function(require,exports){
    require("flashUploader");
    exports.upload = function(){

        $('.btn_upload_pdf').on("click",function(){
            var url = '/lesson/get_token';
            $.get(url,function (response){
                console.log(response);
                $('#lesson_pdf_upload').uploadify({
                    'formData' : response,
                    'swf'      : 'http://admin.nahaodev.com/static/debug/lib/uploadify/2.2/uploadify.swf',
                    'uploader' : 'http://classroom.oa.tizi.com/api/files/', //post to meeting system
                    'multi'    : true,
                    'fileObjName' : 'file',
                    onUploadSuccess: function(file, data, response) {
                        var data = jQuery.parseJSON(data);
                        console.log(data);
                        return false;

                    }
                });
            })
            $('#lesson_upload_modal').modal();
        });

    }

})