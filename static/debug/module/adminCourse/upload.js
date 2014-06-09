define(function(require,exports){
    require("flashUploader");
    exports.addUpload = function(){
//        uploadImages.initUploadImages();//调用初始化方法
        var formData = {'key':$('#new_file_name').val(),'token':$('#nahao_token').val()};
        $('#course_img').uploadify({
            'formData' : formData,
//            'swf'      : staticPath+'lib/uploadify/2.2/uploadify.swf',
            'swf'      : 'http://admin.nahaodev.com/static/debug/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonClass'     : 'choseFileBtn',
//            'buttonText' :"&nbsp;&nbsp;",
//            'fileTypeExts' : '*.jpg; *.png;*.gif',
//            'fileSizeLimit' : '2048KB',
            'fileObjName' : 'file',
////            'width'           :300,
////            'height' :225,
//            'overrideEvents': ['onSelectError','onDialogClose'],
//            onUploadStart:function(file){
//                   console.log(1111);
//            },
//            onSWFReady:function(){
//            },
//            onFallback : function() {
//            },
//            onSelectError : function(file, errorCode, errorMsg) {
//                switch (errorCode) {
//                    case -110:
//                        $.tiziDialog({content:"文件 [" + file.name + "] 过大！每张图片不能超过2M"});
//                        break;
//                    case -120:
//                        $.tiziDialog({content:"文件 [" + file.name + "] 大小异常！不可以上传大小为0的文件"});
//                        break;
//                    case -130:
//                        $.tiziDialog({content:"文件 [" + file.name + "] 类型不正确！不可以上传错误的文件格式"});
//                        break;
//                }
//                return false;
//            },
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
                var img_url_large = 'http://n1a2h3a4o5.qiniudn.com/'+data.key+'?imageView/1/w/'+size.large_width+'/h/'+size.large_height;
                var img_url_general = 'http://n1a2h3a4o5.qiniudn.com/'+data.key+'?imageView/1/w/'+size.general_width+'/h/'+size.general_height;
                var img_url_small = 'http://n1a2h3a4o5.qiniudn.com/'+data.key+'?imageView/1/w/'+size.small_width+'/h/'+size.small_height;

//                var img_url_large = data.large;
//                var img_url_general = data.general;
//                var img_url_small = data.small;
//                var img_url = data.src;
                console.log(img_url);
                console.log(img_url_large);
                console.log(img_url_general);
                console.log(img_url_small);
                $('#img_url_large').attr('src',img_url_large);
                $('#img_url_general').attr('src',img_url_general);
                $('#img_url_small').attr('src',img_url_small);
                $('#img_url').attr('src',img_url);
            }
        });

    }

})