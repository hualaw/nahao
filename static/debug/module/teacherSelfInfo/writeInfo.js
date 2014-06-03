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
                        var hour = $(".dp_hour").val();
                        var mint = $(".dp_mint").val();
                        if(hour!="00"||mint!="00"){
                            fen = hour+":"+mint;
                        }
                        getTime = $dp.cal.getDateStr()+" "+fen;
                        $(".dp_date").val(getTime);//填写日期
                    }
                });
            });
            //结束上课时间
            $(".endClassTime").on("click",function(){
                var picker = WdatePicker({
                    dateFmt:'yyyy-MM-dd',
                    minDate:'%y-%M-#{%d+1}',
                    onpicked:function($dp){
                        var hour = $(".dp_hour").val();
                        var mint = $(".dp_mint").val();
                        if(hour!="00"||mint!="00"){
                            fen = hour+":"+mint;
                        }
                        getTime = $dp.cal.getDateStr()+" "+fen;
                        $(".dp_date").val(getTime);//填写日期
                    }
                });
            });
        });
    }
})
