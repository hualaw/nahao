define(function(require,exports){
    require("flashUploader");

    //upload resource
    exports.upload = function(){
        $('#btn_upload_record').on("click",function(){
            $("#resource_upload_area").hide();
            $("#token_load_area").show();
            var url = '/resource/token';
            //get upload token
            $.get(url,function (response){
                if(response){
                    if(response.token !='' ){
                        $("#token_load_area").hide();
                        $("#resource_upload_area").show();
                        $('#resource_upload_modal').modal();
//                        console.log(response);return false;
                        var formData = {'token' : response.token};
                        //upload to meeting system
                        $('#resource_upload').uploadify({
                            'formData' : response,
                            'swf'      : _swf_url+'/lib/uploadify/2.2/uploadify.swf',
                            'uploader' : 'http://up.qiniu.com', //需要上传的url地址
                            'multi'    : true,
                            'fileObjName' : 'file',
                            onSelect: function(file){
                                $('#resource_upload').uploadify('settings','formData',{'key' : file.name});
                            },
                            onUploadSuccess: function(file, data, response) {
                                var data = jQuery.parseJSON(data);
                                console.log(data);
                                if(data.key && data.key!=''){
                                    var url = '/resource/add';
                                    var data = {
                                        'uri' : data.key
                                    };
                                    //add courseware_id to lesson
                                    $.post(url, data, function(response){
                                        if(response){
                                            alert(response.msg);
                                            if(response.status=='ok'){
                                                window.location.reload();
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    }else{
                        $("#token_display_area").html('token读取失败请重试');
                    }
                }
            })
            $('#lesson_upload_modal').modal();
        });
    }

});