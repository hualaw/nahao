$(function() {

    var totalPage = parseInt($('.total-page').text());

    var toFixString = function(num) {
        var str = num.toString();
        if (str.length == 1) {
            str = "000" + str;
        } else if (str.length == 2) {
            str = "00" + str;
        } else if (str.length == 3) {
            str = "0" + str;
        }
        return str;
    };

    var setObjectPosition = function() {
        $('object').css('width', $('.container').width() + 'px');
        $('object').css('height', $(window).height() - 90 + 'px');
    };

    var validPage = function(page) {
        return page > 0 && page <= totalPage;
    };

    // load page, set current page
    var loadPage = function(page) {
        if (!validPage(page)) {
//            alert('椤电爜涓嶅');
            return;
        }

        $('input').val(page);
        location.hash = page;

        var base_url = $('input').data('base-url');
        var url = base_url.replace('/xxxx', '/' + toFixString(page));
        $('.media-wrapper').fadeOut(function() {
            $('.media-wrapper').flash(url);
            setObjectPosition();
        }).fadeIn();
    };

    var currentPage = function() {
        return parseInt($('input').val());
    };
    var firstPage = function() {
        loadPage(1);
    };
    var nextPage = function() {
        loadPage(currentPage() + 1);
    };
    var prevPage = function() {
        loadPage(currentPage() - 1);
    };
    var lastPage = function() {
        loadPage(totalPage);
    };
    var keyup = function(e) {
        if (e.which === 13) {
            var page = $(this).val();
            loadPage(page);
        }
    };

    // events
    $(window).resize(setObjectPosition);
    $('a.first').click(firstPage);
    $('a.prev').click(prevPage);
    $('a.next').click(nextPage);
    $('a.last').click(lastPage);
    $('input').keyup(keyup);

    // init page
    var page = parseInt(location.hash.slice(1));
    if (!validPage(page)) {
        page = 1;
    }
    loadPage(page);
});