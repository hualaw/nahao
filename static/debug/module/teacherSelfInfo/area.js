define(function(require,exports){
    exports.change_area = function () {
        $('#province').change(function(){
            $.ajax({
                type:"post",
                url:"/selfInfo/get_city_list",
                data:"province="+$('#province').val(),
                dataType:"",
                success:function(msg){
                    if(msg=="")
                    {
                        $('#city').hide();
                        $('#area').hide();
                    }
                    $('#city').empty();
                    var city=eval(msg);
                    $.each(city,function(index,d){
                        $('#city').show().append("<option value="+d.id+">"+d.name+"</option>");
                    })
                    $.ajax({
                        type:"post",
                        url:"/selfInfo/get_county_list",
                        data:"city="+$('#city').val(),
                        dataType:"json",
                        success:function(msg){
                            var area_select = $("#area");
                            if(area_select.length == 0) {
                                area_select = $("<select id='area' name='area' class='area'></select>");
                                $("#area_div").append(area_select);
                            }
                            
                            if(msg == "") {
                               $('#area').remove();
                            }  else {
                                $('#area').empty();
                                var area=eval(msg);
                                $.each(area,function(index,d){
                                    $('#area').append("<option value="+d.id+">"+d.name+"</option>");
                                });
                            }                              
                        }
                    })
                }
            })

        })

        $('#city').change(function(){
            $.ajax({
                type:"post",
                url:"/selfInfo/get_county_list",
                data:"city="+$('#city').val(),
                dataType:"json",
                success:function(msg){
                    if(msg != '') {
                        $('#area').empty();
                        var area=eval(msg);
                        $.each(area,function(index,d){
                            $('#area').append("<option value="+d.id+">"+d.name+"</option>");
                        });
                    }
                }
            })
        })
    }
});