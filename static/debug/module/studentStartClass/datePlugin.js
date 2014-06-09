define(function(require,exports){
      //添加调用日期插件
      exports.addDatePlugin = function(){
          require.async("jQDate",function(){
              //试讲时间
              $(".wtime").on("click",function(){
                  var picker = WdatePicker({
                      dateFmt:'yyyy-MM-dd',
                      minDate:'%y-%M-#{%d+1}',
                      onpicked:function($dp){

                      }
                  });
              });
              //结束上课时间
              // $(".endTime").on("click",function(){
              //     var picker = WdatePicker({
              //         dateFmt:'yyyy-MM-dd',
              //         minDate:'%y-%M-#{%d+1}',
              //         onpicked:function($dp){
              //             var startTime = $(".startTime").val();
              //             var endTime = $(".endTime").val();
              //             var num1 = parseInt(startTime.replace(/-/gi,""));
              //             var num2 = parseInt(endTime.replace(/-/gi,""));
              //             if(startTime==""){
              //                 $.tiziDialog({content:"请先填写开始时间"});
              //                 $(".endTime").val("");
              //             }
              //             if(num2-num1<0){
              //                 $.tiziDialog({content:"结束时间需要大于开始时间"});
              //                 $(".endTime").val("");
              //             }
              //         }
              //     });
              // });
      });
     }
})