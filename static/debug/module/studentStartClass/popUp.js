define(function(require,exports){
	require("naHaoDialog")
    exports.popUp = function (obj){
    	$.tiziDialog({
            title:false,
            ok:false,
            icon:false,
            padding:0,
            content:$(obj).html()
        });
    }
})