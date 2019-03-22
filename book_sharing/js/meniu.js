jQuery(function($) {
    $('.navToggle').click(function() {
        if($('nav').hasClass('expanded'))
            $('nav').removeClass('expanded');
        else $('nav').addClass('expanded');
    });

    $('#search').click(function() {
        $('.search').toggle();
    });
});