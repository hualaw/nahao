define(function(require,exports){
    require("flashUploader");
    exports.addUpload = function(){
//        uploadImages.initUploadImages();//调用初始化方法

        $('#course_img').uploadify({
//            'formData' : {'session_id':'TZID'},
//            'swf'      : staticPath+'lib/uploadify/2.2/uploadify.swf',
            'swf'      : 'http://admin.nahaodev.com/static/debug/lib/uploadify/2.2/uploadify.swf',
            'uploader' : '/course/upload' //需要上传的url地址
//            'multi'    : false,
//            'buttonClass'     : 'choseFileBtn',
//            'buttonText' :"&nbsp;&nbsp;",
//            'fileTypeExts' : '*.jpg; *.png;*.gif',
//            'fileSizeLimit' : '2048KB',
//            'fileObjName' : 'course_img',
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
//            onUploadSuccess: function(file, data, response) {
//                //var json = JSON.parse(data);
//                var json = {};
//                if(json.code == 1){
//
//                }else{
//
//                }
//
//            },
//            onUploadComplete: function(file) {
//            }
        });

    }

})