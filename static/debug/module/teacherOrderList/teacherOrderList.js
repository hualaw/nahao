define(function(require,exports){
      //添加调用日期插件
      exports.addDatePlugin = function(){
          require.async("jQDate",function(){
              //开始上课时间
              $(".startClassTime").on("click",function(){
                  var picker = WdatePicker({
                      dateFmt:'yyyy-MM-dd',
                      minDate:'%y-%M-#{%d+1}',
                      onpicked:function($dp){

                      }
                  });
              });
              //结束上课时间
              $(".endClassTime").on("click",function(){
                  var picker = WdatePicker({
                      dateFmt:'yyyy-MM-dd',
                      minDate:'%y-%M-#{%d+1}',
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
})
