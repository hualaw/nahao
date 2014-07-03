define(function(require,exports){
    require("flashUploader");
    var uploadify_swf = siteUrl + 'static/'+ staticVersion +'/lib/uploadify/2.2/uploadify.swf';
    var upload_url = 'http://up.qiniu.com';
    var image_host = 'http://n1a2h3a4o5.qiniudn.com/';
    exports.addUpload1 = function(div_id){
        //upload teacher qualification img
        var qualificationImgFormData = {'key':$('#new_teacher_auth_img').val(),'token':$('#nahao_token').val()};
        $('#' + div_id).uploadify({
            'formData' : qualificationImgFormData,
            'swf'      : uploadify_swf,
            'uploader' : upload_url, //需要上传的url地址
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
                var img_url_small = image_host+data.key+'?imageView/1/w/'+size.small_width+'/h/'+size.small_height;

                /*添加教师资格证书图片*/
                $("#teacher_auth_img").val(data.key);
                if($(".icon_upload1").length > 0){
                    $(".icon_upload1").hide();
                }
                var imgTag = '<img src="'+img_url_small+'"/><b class="uploadTip">教师资格证书</b><input type="file" name="up_teacher_auth_img" id="up_teacher_auth_img" class="file fl" multiple="true" style="display:none"/>';
                $(".md_upload .ImageSpan01").html(imgTag).show();
                var oldImgKey = $("#new_teacher_auth_img").val();
                var oldTimeStr = oldImgKey.substr(8, 10);
                var date = new Date();
                var newTimeStr = Math.ceil(date.getTime() / 1000);
                //用户可能更改上传的图片, 这里更新img_key
                var newImgKey = oldImgKey.replace(oldTimeStr, newTimeStr);
                $("#new_teacher_auth_img").val(newImgKey);
                seajs.use("module/teacherSelfInfo/upload", function (ex){
                    ex.addUpload1("up_teacher_auth_img");
                });
            }
        });
    }
    
    exports.addUpload2 = function(div_id){
        //upload teacher title authentication img
        var titleImgFormData = {'key':$('#new_title_auth_img').val(),'token':$('#nahao_token').val()};
        $('#' + div_id).uploadify({
            'formData' : titleImgFormData,
            'swf'      : uploadify_swf,
            'uploader' : upload_url, //需要上传的url地址
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
                var img_url_small = image_host+data.key+'?imageView/1/w/'+size.small_width+'/h/'+size.small_height;

                /*添加教师职称证书图片*/
                $("#title_auth_img").val(data.key);
                if($(".icon_upload2").length > 0){
                    $(".icon_upload2").hide();
                }
                var imgTag = '<img src="'+img_url_small+'"/><b class="uploadTip">教师职称证书</b><input type="file" name="up_title_auth_img" id="up_title_auth_img" class="file fl" multiple="true" style="display:none"/>';
                $(".md_upload .ImageSpan02").html(imgTag).show();
                //用户可能更改上传的图片, 这里更新img_key
                var oldImgKey = $("#new_work_auth_img").val();
                var oldTimeStr = oldImgKey.substr(8, 10);
                var date = new Date();
                var newTimeStr = Math.ceil(date.getTime() / 1000);
                var newImgKey = oldImgKey.replace(oldTimeStr, newTimeStr);
                $("#new_title_auth_img").val(newImgKey);
                seajs.use("module/teacherSelfInfo/upload", function (ex){
                    ex.addUpload2("up_title_auth_img");
                });
            }
        });
    }
    
    exports.addUpload3 = function(div_id){
        //upload teacher work authentication img
        var workImgFormData = {'key':$('#new_work_auth_img').val(),'token':$('#nahao_token').val()};
        $('#' + div_id).uploadify({
            'formData' : workImgFormData,
            'swf'      : uploadify_swf,
            'uploader' : upload_url, //需要上传的url地址
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
                var img_url_small = image_host+data.key+'?imageView/1/w/'+size.small_width+'/h/'+size.small_height;
//               /*添加学校工作证图片*/
                $("#work_auth_img").val(data.key);
                if($(".icon_upload3").length > 0){
                    $(".icon_upload3").hide();
                }
                var imgTag = '<img src="'+img_url_small+'"/><b class="uploadTip">学校工作证</b><input type="file" name="up_work_auth_img" id="up_work_auth_img" class="file fl" multiple="true" style="display:none"/>';
                $(".md_upload .ImageSpan03").html(imgTag).show();
                //用户可能更改上传的图片, 这里更新img_key
                var oldImgKey = $("#new_work_auth_img").val();
                var oldTimeStr = oldImgKey.substr(8, 10);
                var date = new Date();
                var newTimeStr = Math.ceil(date.getTime() / 1000);
                var newImgKey = oldImgKey.replace(oldTimeStr, newTimeStr);
                $("#new_work_auth_img").val(newImgKey);
                seajs.use("module/teacherSelfInfo/upload", function (ex){
                    ex.addUpload3("up_work_auth_img");
                });
            }
        });
    }

});