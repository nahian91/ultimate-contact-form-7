(function($){
    document.addEventListener('wpcf7mailsent', function(event){
        if (typeof ucf7eRedirectData !== 'undefined') {
            if (event.detail.contactFormId == ucf7eRedirectData.formId) {
                window.location.href = ucf7eRedirectData.redirectUrl;
            }
        }
    }, false);
})(jQuery);
