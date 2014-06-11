define(function(require,exports){
    exports.change_area = function () {
//        $('#province').change(function(){
//            $.ajax({
//                type:"post",
//                url:"/selfInfo/get_city_list",
//                data:"province="+$('#province').val(),
//                dataType:"json",
//                success:function(msg){
//                    alert(msg);
//                    if(msg=="")
//                    {
//                        $('#city').hide();
//                        $('#area').hide();
//                    }
//                    $('#city').empty();
//                    var city=eval(msg);
//                    $.each(city,function(index,d){
//                        $('#city').show().append("<option value="+d.id+">"+d.name+"</option>");
//                    })
//                    $.ajax({
//                        type:"post",
//                        url:"/teacher/area",
//                        data:"city="+$('#city').val(),
//                        dataType:"json",
//                        success:function(msg){
//                            if(msg=="")
//                            {
//                               $('#area').hide();
//                            }
//                            $('#area').empty();
//                            var area=eval(msg);
//                            $.each(area,function(index,d){
//                                $('#area').show().append("<option value="+d.id+">"+d.name+"</option>");
//                            })
//                        }
//                    })
//                }
//            })
//
//        })
//
//        $('#city').change(function(){
//            $.ajax({
//                type:"post",
//                url:"/teacher/area",
//                data:"city="+$('#city').val(),
//                dataType:"json",
//                success:function(msg){
//                    $('#area').empty();
//                    var area=eval(msg);
//                    $.each(area,function(index,d){
//                        $('#area').append("<option value="+d.id+">"+d.name+"</option>");
//                    })
//                }
//            })
//        })
        alert(1)
        $('#province').live("click",function(){
            alert(1111);
        });
    }
});