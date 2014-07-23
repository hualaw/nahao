define(function (require, exports) {
    $('.show_phone').hover(function(){
        var tel=$(this).data('tel');
        $(this).popover({trigger: 'hover',content:tel});
    })
})