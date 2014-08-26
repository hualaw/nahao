define(function(require,exports){
    require("flashUploader");
    exports.addUpload = function(){
        //upload img
        var imgFormData = {'key':$('#new_img_file_name').val(),'token':$('#nahao_token').val()};
        $('#course_img').uploadify({
            'formData' : imgFormData,
            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf?'+Math.random(),
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonClass'     : 'choseFileBtn',
            'fileObjName' : 'file',
            'fileTypeExts' : '*.png;*.jpg;',
//            'fileSizeLimit' : 2048000,
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var img_url = data.key;
                var img_url_general = _qiniu_url+img_url+'?imageView/1/w/'+_img_url_general_width+'/h/'+_img_url_general_height;
                var img_url_live = _qiniu_url+img_url+'?imageView/1/w/'+_img_url_live_width+'/h/'+_img_url_live_height;
                var img_url_index = _qiniu_url+img_url+'?imageView/1/w/'+_img_url_index_width+'/h/'+_img_url_index_height;
                var img_url_bug_before_top = _qiniu_url+img_url+'?imageView/1/w/'+_img_url_bug_before_top_width+'/h/'+_img_url_bug_before_top_height;
                var img_url_buy_before_recommend = _qiniu_url+img_url+'?imageView/1/w/'+_img_url_buy_before_recommend_width+'/h/'+_img_url_buy_before_recommend_height;
                var img_url_recent_view = _qiniu_url+img_url+'?imageView/1/w/'+_img_url_recent_view_width+'/h/'+_img_url_recent_view_height;

                $('#img_url_general').attr('src',img_url_general);
                $('#img_url_live').attr('src',img_url_live);
                $('#img_url_index').attr('src',img_url_index);
                $('#img_url_bug_before_top').attr('src',img_url_bug_before_top);
                $('#img_url_buy_before_recommend').attr('src',img_url_buy_before_recommend);
                $('#img_url_recent_view').attr('src',img_url_recent_view);
                $('#img_url').attr('src',img_url);

                $.get('/course/get_token',function(response){
                    $('#new_img_file_name').val(response.data.img_file_name);
                    $('#nahao_token').val(response.data.token);
                    exports.addUpload();
                })
            }
        });

    }
	exports.video_upload = function (){
        //upload video
        var videoFormData = {'key':$('#new_video_file_name').val(),'token':$('#nahao_video_token').val()};
        $('#course_video').uploadify({
            'formData' : videoFormData,
            'swf'      :  _swf_url+'/lib/uploadify/2.2/uploadify.swf?'+Math.random(),
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : true,
            'buttonClass'     : 'choseFileBtn',
            'fileObjName' : 'file',
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                $('#video_url').text(data.key);
                $('#video_url_show').text(_qiniu_video_url+data.key);
            }
        });
	}

})
