define(function(require,exports){
    // focus的时候出现提示文字
    var getInfoObj=function(){
        return  $(this).parent().find(".Validform_tip_info");
    };
    $("[datatype]").focusin(function(){
        if(this.timeout){clearTimeout(this.timeout);}
        var infoObj=getInfoObj.call(this);
        if(infoObj.siblings(".Validform_right").length!=0){
            return; 
        }
        infoObj.show().next().hide();
        
    }).focusout(function(){
        var infoObj=getInfoObj.call(this);
        this.timeout=setTimeout(function(){
            infoObj.hide().next(".Validform_wrong,.Validform_right,.Validform_loading").show();
        },0);
    });
    
    // var getInfoObj=function(){
    //     return  $(this).parent().find(".Validform_tip_info");
    // };
    // $("[datatype]").live("focusin",function(){
    //     if(this.timeout){clearTimeout(this.timeout);}
    //     var infoObj=getInfoObj.call(this);
    //     infoObj.prev('input').removeClass('Validform_error');
    //     if(infoObj.siblings(".Validform_right").length!=0){
    //         return; 
    //     }
    //     infoObj.show().next().hide();
        
    // }).live("focusout",function(){
    //     var infoObj=getInfoObj.call(this);
    //     // infoObj.prev('input').addClass('Validform_error');
    //     this.timeout=setTimeout(function(){
    //         infoObj.hide().next(".Validform_wrong,.Validform_right,.Validform_loading").show();
    //     },0);
    // });
})
