define(function(require,exports){
    //薪酬调查帮助脚本
    exports.icon_help = function(){
        $(".md_pay .icon_help").hover(function(){
            $(".md_pay .icon_info").show();
        },function(){
            $(".md_pay .icon_info").hide();
        })
    }
})
