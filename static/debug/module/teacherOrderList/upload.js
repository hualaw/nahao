define(function(require,exports){
	exports.pdf_upload = function (btn_name,token,class_id){
        //upload video
        var meeting_url = _meeting_url+'api/files/';
        var _swf_url = siteUrl  + '/static/debug/lib/uploadify/2.2/uploadify.swf?'+Math.random();
        $('#course_pdf').uploadify({
            'formData' : {
    			'token':token
            },
            'swf'      :  _swf_url,
            'uploader' : meeting_url, 
            'multi'    : false,
            'buttonClass'     : 'choseFileBtn',
            'buttonText': '上传 '+btn_name+' pdf 讲义',
            'fileObjName' : 'fileobj',
            'fileTypeExts' : '*.pdf',
            'fileSizeLimit' : '10MB',
            'height': 100,
			'width': 350,
            onUploadSuccess: function(file,res, response) {
                var arr = jQuery.parseJSON(res);
                if(arr.id && arr.id>0){
                    console.log(arr);
                    url = '/orderList/add_courseware';
                    data = {
                        'class_id' : class_id,
                        'courseware_id' : arr.id,
                        'create_time' : arr.created_at,
                        'filename' : arr.filename,
                        'filesize' : arr.filesize,
                        'filetype' : arr.filetype
                    };
                    console.log(url);
                    $.post(url, data, function(response){
                        console.log(response);
                        if(response){
                            alert(response.msg);
                            window.location.reload();
                        }
                    });
                }
            }
        });
	}

})
