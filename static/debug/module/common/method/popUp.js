define(function(require,exports){
	require("naHaoDialog");
    exports.popUp = function (obj){
    	$.tiziDialog({
            title:false,
            close:null,
            ok:false,
            icon:null,
            padding:0,
            content:$(obj).html()
        });
    }
})