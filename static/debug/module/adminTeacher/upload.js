define(function(require,exports){
    require("flashUploader");
    exports.addUpload = function(){
        var qualificationImgFormData = {'key':$('#teacher_auth_img').val(),'token':$('#nahao_token').val()};
        $('#up_teacher_auth_img').uploadify({
            'formData' : qualificationImgFormData,
            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'buttonText' :"点击上传",
            'buttonClass' : 'choseFileBtn',
            'fileObjName' : 'file',

            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                //alert(data);
                var size = {
                    'general_width' : 169,
                    'general_height' : 227
                };
                var img_url = data.key;
                var img_url_general = _qiniu_url+data.key+'?imageView/1/w/'+size.general_width+'/h/'+size.general_height;

//                console.log(img_url);
//                console.log(img_url_general);
                $("#teacher_authimg").val(img_url);
                $('#img_url_general').attr('src',img_url_general);
                /*添加教师资格证书图片*/
            }
        });
        //upload teacher title authentication img
        var titleImgFormData = {'key':$('#title_auth_img').val(),'token':$('#nahao_token').val()};
        $('#up_title_auth_img').uploadify({
            'formData' : titleImgFormData,
            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonText' :"点击上传",
            'buttonClass' : 'choseFileBtn',
            'fileObjName' : 'file',
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var size = {
                    'general_width' : 169,
                    'general_height' : 227
                };
                var img_url = data.key;
                var img_url_general = _qiniu_url+data.key+'?imageView/1/w/'+size.general_width+'/h/'+size.general_height;
//                console.log(img_url);
//                console.log(img_url_general);
                $("#title_authimg").val(img_url);
                $('#img_title_general').attr('src',img_url_general);
                /*添加教师职称证书图片*/
            }
        });

        //upload teacher work authentication img
        var workImgFormData = {'key':$('#work_auth_img').val(),'token':$('#nahao_token').val()};
        $('#up_work_auth_img').uploadify({
            'formData' : workImgFormData,
            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonText' :"点击上传",
            'buttonClass' : 'choseFileBtn',
            'fileObjName' : 'file',
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var size = {
                    'general_width' : 169,
                    'general_height' : 227
                };
                var img_url = data.key;
                var img_url_general = _qiniu_url+data.key+'?imageView/1/w/'+size.general_width+'/h/'+size.general_height;
//                console.log(img_url);
//                console.log(img_url_general);
//               /*添加学校工作证图片*/
                $("#work_authimg").val(img_url);
                $("#img_work_general").attr('src',img_url_general);
            }
        });
    }

})