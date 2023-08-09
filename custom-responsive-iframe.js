(function($) {
    $(document).ready(function() {
        $.get(custom_iframe_vars.iframe_url, function(data) {
            $('#custom-iframe-container').html(data);
        });
    });
})(jQuery);
