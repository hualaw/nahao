define(function(require,exports){
    require("flashUploader");
    exports.addUpload = function(){
        //upload img
        var imgFormData = {'key':$('#new_img_file_name').val(),'token':$('#nahao_token').val()};
        $('#round_img').uploadify({
            'formData' : imgFormData,
            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonClass'     : 'choseFileBtn',
            'fileObjName' : 'file',
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var size = {
                    'large_width' : 216,
                    'large_height' : 290,
                    'general_width' : 169,
                    'general_height' : 227,
                    'small_width' : 49,
                    'small_height' : 66
                };
                var img_url = data.key;
                var img_url_large = _qiniu_url+data.key+'?imageView/1/w/'+size.large_width+'/h/'+size.large_height;
                var img_url_general = _qiniu_url+data.key+'?imageView/1/w/'+size.general_width+'/h/'+size.general_height;
                var img_url_small = _qiniu_url+data.key+'?imageView/1/w/'+size.small_width+'/h/'+size.small_height;

//                var img_url_large = data.large;
//                var img_url_general = data.general;
//                var img_url_small = data.small;
//                var img_url = data.src;
                $('#img_url_large').attr('src',img_url_large);
                $('#img_url_general').attr('src',img_url_general);
                $('#img_url_small').attr('src',img_url_small);
                $('#img_url').attr('src',img_url);
            }
        });

        //upload video
        var videoFormData = {'key':$('#new_video_file_name').val(),'token':$('#nahao_token').val()};
        $('#round_video').uploadify({
            'formData' : videoFormData,
            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonClass'     : 'choseFileBtn',
            'fileObjName' : 'file',
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                console.log(data);
            }
        });
    }

})