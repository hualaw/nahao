define(function(require,exports){
    require("flashUploader");
    exports.addUpload = function(){
        var qualificationImgFormData = {'key':$('#key').val(),'token':$('#nahao_token').val()};
        $('#focus').uploadify({
            'formData' : qualificationImgFormData,
            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'buttonText' :"添加",
            'buttonClass' : 'choseFileBtn',
            'fileObjName' : 'file',
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var size = {
                    'general_width' : index_focus_width,
                    'general_height' : index_focus_height
                };
                var img_url = data.key;
                var img_url_general = _qiniu_url+data.key+'?imageView/2/w/'+size.general_width+'/h/'+size.general_height;
                $("#img_src").val(img_url);
                $('#img_url_general').attr('src',img_url_general);
            }
        });
    }
})