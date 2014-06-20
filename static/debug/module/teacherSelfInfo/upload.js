define(function(require,exports){
    require("flashUploader");
    exports.addUpload = function(){
        //upload teacher qualification img
        var qualificationImgFormData = {'key':$('#new_teacher_auth_img').val(),'token':$('#nahao_token').val()};
        $('#up_teacher_auth_img').uploadify({
            'formData' : qualificationImgFormData,
            'swf'      : siteUrl + '/static/debug/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'buttonText' :"&nbsp;&nbsp;",
            'buttonClass' : 'choseFileBtn',
            'fileObjName' : 'file',
            'width'       :300,
            'height'      :225,
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var size = {
//                    'large_width' : 216,
//                    'large_height' : 290,
//                    'general_width' : 169,
//                    'general_height' : 227,
                    'small_width' : 300,
                    'small_height' : 225
                };
                var img_url = data.key;
                var img_url_small = 'http://n1a2h3a4o5.qiniudn.com/'+data.key+'?imageView/1/w/'+size.small_width+'/h/'+size.small_height;

                console.log(img_url);
                console.log(img_url_small);
                /*添加教师资格证书图片*/
                $("#teacher_auth_img").val(data.key);
                $(".icon_upload1").hide();
                var imgTag = '<img src="'+img_url_small+'"/><b class="uploadTip">教师资格证书</b>';
                $(".md_upload .ImageSpan01").html(imgTag).show();
            }
        });
        //upload teacher title authentication img
        var titleImgFormData = {'key':$('#new_title_auth_img').val(),'token':$('#nahao_token').val()};
        $('#up_title_auth_img').uploadify({
            'formData' : titleImgFormData,
            'swf'      : 'http://teacher.nahaodev.com/static/debug/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonText' :"&nbsp;&nbsp;",
            'buttonClass' : 'choseFileBtn',
            'fileObjName' : 'file',
            'width'       :300,
            'height'      :225,
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var size = {
                    'small_width' : 300,
                    'small_height' : 225
                };
                var img_url = data.key;
                var img_url_small = 'http://n1a2h3a4o5.qiniudn.com/'+data.key+'?imageView/1/w/'+size.small_width+'/h/'+size.small_height;

                console.log(img_url);
                console.log(img_url_small);
                /*添加教师职称证书图片*/
                $("#title_auth_img").val(data.key);
                $(".icon_upload2").hide();
                var imgTag = '<img src="'+img_url_small+'"/><b class="uploadTip">教师职称证书</b>';
                $(".md_upload .ImageSpan02").html(imgTag).show();
            }
        });

        //upload teacher work authentication img
        var workImgFormData = {'key':$('#new_work_auth_img').val(),'token':$('#nahao_token').val()};
        $('#up_work_auth_img').uploadify({
            'formData' : workImgFormData,
            'swf'      : 'http://teacher.nahaodev.com/static/debug/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonText' :"&nbsp;&nbsp;",
            'buttonClass' : 'choseFileBtn',
            'fileObjName' : 'file',
            'width'       :300,
            'height'      :225,
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var size = {
                    'small_width' : 300,
                    'small_height' : 225
                };
                var img_url = data.key;
                var img_url_small = 'http://n1a2h3a4o5.qiniudn.com/'+data.key+'?imageView/1/w/'+size.small_width+'/h/'+size.small_height;
                console.log(img_url);
                console.log(img_url_small);
//               /*添加学校工作证图片*/
                $("#work_auth_img").val(data.key);
                $(".icon_upload3").hide();
                var imgTag = '<img src="'+img_url_small+'"/><b class="uploadTip">教师职称证书</b>';
                $(".md_upload .ImageSpan03").html(imgTag).show();
            }
        });
        



    }

})