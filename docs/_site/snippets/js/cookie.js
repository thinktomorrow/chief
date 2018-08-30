;(function($){

    if(!(document.cookie.indexOf('cookieaccepted') >= 0))
    {
        // Cookie not accepted yet or first time visit
        expiry = new Date();
        expiry.setFullYear(expiry.getFullYear() + 1);

        document.cookie = "cookieaccepted=yes; expires=" + expiry.toGMTString();

        // Show notification
        $(".close-cookie").parents('#note').removeClass('hidden');
    }

    $(".close-cookie").click(function (e) {
        $(this).parents('#note').slideUp('slow');
    });

})(jQuery);