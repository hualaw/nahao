define(function(require,exports){
      //添加调用日期插件
      exports.addDatePlugin = function(){
          require.async("jQDate",function(){
              //开始上课时间
              $(".startClassTime").on("click",function(){
                  var picker = WdatePicker({
                      dateFmt:'yyyy-MM-dd',
//                      minDate:'%y-%M-#{%d+1}',
                      minDate:'2014-06-01',
                      onpicked:function($dp){

                      }
                  });
              });
              //结束上课时间
              $(".endClassTime").on("click",function(){
                  var picker = WdatePicker({
                      dateFmt:'yyyy-MM-dd',
//                      minDate:'%y-%M-#{%d+1}',
					  minDate:'2014-06-01',
                      onpicked:function($dp){
                          var startClassTime = $(".startClassTime").val();
                          var endClassTime = $(".endClassTime").val();
                          var num1 = parseInt(startClassTime.replace(/-/gi,""));
                          var num2 = parseInt(endClassTime.replace(/-/gi,""));
                          if(startClassTime==""){
                              $.tiziDialog({content:"请先填写开始时间"});
                              $(".endClassTime").val("");
                          }
                          if(num2-num1<0){
                              $.tiziDialog({content:"结束时间需要大于开始时间"});
                              $(".endClassTime").val("");
                          }
                      }
                  });
              });
      });
     }
	//添加调用日期插件
	exports.uploadPdf = function(){
		$('.updload_pdf').on('click',function(){
			var isChrome = window.navigator.userAgent.indexOf("Chrome") !== -1;
            if(!isChrome){
            	alert("只支持谷歌浏览器");
            	return false;
            }
			var class_id = $(this).attr('data-classID');
			var class_name = $(this).attr('data-name');
			var upload = require("module/teacherOrderList/upload.js");
			$.tiziDialog({
       			icon: 'face-smile',//face-smile survey2 survey succeed error warning
       			title: '上传'+class_name+'的讲义',
                content:'请上传该课的pdf讲义课件，新的讲义将覆盖之前的<div id="course_pdf" data-classID="'+class_id+'"></div><p class="token_msg"></p>',
            });
            var url = '/orderList/getToken/?tmp='+((new Date).valueOf());
			$.get(url,function(data){
				if(data.token !='' ){
					upload.pdf_upload(class_name,data.token,class_id);
				}else{
					$(".token_msg").html('token读取失败请重试');
				}
            });
		});
		
		$(".reload_courseware").on("click",function(){
            var url = '/orderList/reload/?tmp='+((new Date).valueOf());
            var data = {
                'classroom_id' : $(this).data("classroom_id")
            };
            $.post(url,data,function(response){
                alert(response.msg)
                window.location.reload();
            })
        });
	}
})
