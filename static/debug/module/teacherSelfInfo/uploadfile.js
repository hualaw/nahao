/**
 * Created by 91waijiao on 14-5-29.
 */
define(function(require,exports){
    require("flashUploader");
    exports.addUploadCredit = function(){
        uploadImages.initUploadImages();//调用初始化方法
    }
    var uploadImages = {
        //上传图片
        initUploadImages:function(){
            var that = this;
            $(".uploada").each(function(){
                var node = $(this);
                var upload_id = node.attr('data-fileId');
                $('#'+upload_id).uploadify({
                    'formData' : {'session_id':'TZID'},
                    'swf'      : staticPath+'lib/uploadify/2.2/uploadify.swf',
                    'uploader' : '/upload/XXXX?id='+upload_id, //需要上传的url地址
                    'multi'    : false,
                    'buttonClass'     : 'choseFileBtn',
                    'buttonText' :"&nbsp;&nbsp;",
                    'fileTypeExts' : '*.jpg; *.png;*.gif',
                    'fileSizeLimit' : '2048KB',
                    'fileObjName' : upload_id,
                    'width'           :300,
                    'height' :225,
                    'overrideEvents': ['onSelectError','onDialogClose'],
                    onUploadStart:function(file){
                    },
                    onSWFReady:function(){
                    },
                    onFallback : function() {
                    },
                    onSelectError : function(file, errorCode, errorMsg) {
                        switch (errorCode) {
                            case -110:
                                $.tiziDialog({content:"文件 [" + file.name + "] 过大！每张图片不能超过2M"});
                                break;
                            case -120:
                                $.tiziDialog({content:"文件 [" + file.name + "] 大小异常！不可以上传大小为0的文件"});
                                break;
                            case -130:
                                $.tiziDialog({content:"文件 [" + file.name + "] 类型不正确！不可以上传错误的文件格式"});
                                break;
                        }
                        return false;
                    },
                    onUploadSuccess: function(file, data, response) {
                        //var json = JSON.parse(data);
                        var json = {};
                        if(json.code == 1){

                        }else{

                        }

                    },
                    onUploadComplete: function(file) {
                    }
                });
            });
        },
        drawImage:function(src,width,height,upload_id){
            var image=new Image();
            var Img = new Image();//返回值
            image.src=src;
            image.onload = function(){
                if(image.width>width||image.height>height){
                    //现有图片只有宽或高超了预设值就进行js控制
                    w=image.width/width;
                    h=image.height/height;
                    if(w>h){
                        //宽比高大
                        //定下宽度为width的宽度
                        Img.width=width;
                        //以下为计算高度
                        Img.height=image.height/w;
                    }else{
                        //高比宽大
                        //定下宽度为height高度
                        Img.height=height;
                        //以下为计算高度
                        Img.width=image.width/h;
                    }
                }else{
                    h=image.height/height;
                    Img.width=image.width/h;
                    Img.height=height;
                }
                //$('.'+upload_id).html('<img src="'+src+'" class="picture_urls" width="'+Img.width+'" height="'+Img.height+'"/>');
            }
        }
    }
})