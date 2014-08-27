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
                    'general_width' : teacher_auth_width,
                    'general_height' : teacher_auth_height
                };
                var img_url = data.key;
                var img_url_general = _qiniu_url+data.key+'?imageView/2/w/'+size.general_width+'/h/'+size.general_height;

//                console.log(img_url);
//                console.log(img_url_general);
                $("#teacher_authimg").val(img_url);
                $('#img_url_general').attr('src',img_url_general);
                $('#show_3001').html('300*225');
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
                    'general_width' : teacher_auth_width,
                    'general_height' : teacher_auth_height
                };
                var img_url = data.key;
                var img_url_general = _qiniu_url+data.key+'?imageView/2/w/'+size.general_width+'/h/'+size.general_height;
//                console.log(img_url);
//                console.log(img_url_general);
                $("#title_authimg").val(img_url);
                $('#img_title_general').attr('src',img_url_general);
                $('#show_3002').html('300*225');
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
                    'general_width' : teacher_auth_width,
                    'general_height' : teacher_auth_height
                };
                var img_url = data.key;
                var img_url_general = _qiniu_url+data.key+'?imageView/2/w/'+size.general_width+'/h/'+size.general_height;
//                console.log(img_url);
//                console.log(img_url_general);
//               /*添加学校工作证图片*/
                $("#work_authimg").val(img_url);
                $("#img_work_general").attr('src',img_url_general);
                $('#show_3003').html('300*225');
            }
        });


        var titleImgFormData = {'key':$('#avatar_img').val(),'token':$('#nahao_token').val()};
        $('#up_avatar_img').uploadify({
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
                    'general_width' : teacher_title_width,
                    'general_height' : teacher_title_height
                };
                var sizehundred = {
                    'general_width' : 100,
                    'general_height' : 100
                };
                var sizeseventy = {
                    'general_width' : 70,
                    'general_height' : 70
                };
                var sizefifty = {
                    'general_width' : 50,
                    'general_height' : 50
                };
                var sizefortyfive = {
                    'general_width' : 45,
                    'general_height' : 45
                };
                var img_url = data.key;
                var img_url_general = _qiniu_url+data.key+'?imageView/2/w/'+size.general_width+'/h/'+size.general_height;
                var img_urlhundred_general = _qiniu_url+data.key+'?imageView/2/w/'+sizehundred.general_width+'/h/'+sizehundred.general_height;
                var img_urlseventy_general = _qiniu_url+data.key+'?imageView/2/w/'+sizeseventy.general_width+'/h/'+sizeseventy.general_height;
                var img_urlfifty_general = _qiniu_url+data.key+'?imageView/2/w/'+sizefifty.general_width+'/h/'+sizefifty.general_height;
                var img_urlfortyfive_general = _qiniu_url+data.key+'?imageView/2/w/'+sizefortyfive.general_width+'/h/'+sizefortyfive.general_height;
//                console.log(img_url);
//                console.log(img_url_general);
                $('#show_130').html('130*130');
                $('#show_100').html('100*100');
                $('#show_70').html('70*70');
                $('#show_50').html('50*50');
                $('#show_45').html('45*45');
                $("#teacher_avatar_img").val(img_url);
                $('#img_avatar').attr('src',img_url_general);
                $('#img_urlhundred_general').attr('src',img_urlhundred_general);
                $('#img_urlseventy_general').attr('src',img_urlseventy_general);
                $('#img_urlfifty_general').attr('src',img_urlfifty_general);
                $('#img_urlfortyfive_general').attr('src',img_urlfortyfive_general);
                /*添加教师职称证书图片*/
            }
        });
    }

})