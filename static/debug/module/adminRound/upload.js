define(function(require,exports){
    require("flashUploader");
    exports.addUpload = function(){
        //upload img
//        var imgFormData = {'key':$('#new_img_file_name').val(),'token':$('#nahao_token').val()};
        $('#round_img').uploadify({
//            'formData' : imgFormData,
            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
            'multi'    : false,
            'buttonClass'     : 'choseFileBtn',
            'fileObjName' : 'file',
            'fileTypeExts' : '*.png;*.jpg;',
            onSelect: function(file){
                $('#round_img').uploadify('settings','formData',{'key':$('#new_img_file_name').val(),'token':$('#nahao_token').val()});
            },
            onUploadSuccess: function(file, data, response) {
                var data = jQuery.parseJSON(data);
                var img_url = data.key;
                var img_url_s1 = _qiniu_url+img_url+'/c.'+_img_url_course_w1+'.'+_img_url_course_h1+'.jpg';
                var img_url_s2 = _qiniu_url+img_url+'/c.'+_img_url_course_w2+'.'+_img_url_course_h2+'.jpg';
                var img_url_s3 = _qiniu_url+img_url+'/c.'+_img_url_course_w3+'.'+_img_url_course_h3+'.jpg';
                var img_url_s4 = _qiniu_url+img_url+'/c.'+_img_url_course_w4+'.'+_img_url_course_h4+'.jpg';
                var img_url_s5 = _qiniu_url+img_url+'/c.'+_img_url_course_w5+'.'+_img_url_course_h5+'.jpg';
                var img_url_s6 = _qiniu_url+img_url+'/c.'+_img_url_course_w6+'.'+_img_url_course_h6+'.jpg';
                var img_url_s7 = _qiniu_url+img_url+'/c.'+_img_url_course_w7+'.'+_img_url_course_h7+'.jpg';
                var img_url_s8 = _qiniu_url+img_url+'/c.'+_img_url_course_w8+'.'+_img_url_course_h8+'.jpg';
                var img_url_s9 = _qiniu_url+img_url+'/c.'+_img_url_course_w9+'.'+_img_url_course_h9+'.jpg';
                var img_url_s10 = _qiniu_url+img_url+'/c.'+_img_url_course_w10+'.'+_img_url_course_h10+'.jpg';

                $('#img_url_s1').attr('src',img_url_s1);
                $('#img_url_s2').attr('src',img_url_s2);
                $('#img_url_s3').attr('src',img_url_s3);
                $('#img_url_s4').attr('src',img_url_s4);
                $('#img_url_s5').attr('src',img_url_s5);
                $('#img_url_s6').attr('src',img_url_s6);
                $('#img_url_s7').attr('src',img_url_s7);
                $('#img_url_s8').attr('src',img_url_s8);
                $('#img_url_s9').attr('src',img_url_s9);
                $('#img_url_s10').attr('src',img_url_s10);
                $('#img_url').attr('src',img_url);
            }
        });

        //upload video
//        var videoFormData = {'key':$('#new_video_file_name').val(),'token':$('#nahao_token').val()};
//        $('#round_video').uploadify({
//            'formData' : videoFormData,
//            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
//            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
//            'multi'    : true,
//            'buttonClass'     : 'choseFileBtn',
//            'fileObjName' : 'file',
//            onUploadSuccess: function(file, data, response) {
//                var data = jQuery.parseJSON(data);
////                console.log(data);
//            }
//        });
    }

})