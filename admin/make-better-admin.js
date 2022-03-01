var strict;

jQuery(document).ready(function ($) {
    /**
     * DEACTIVATION FEEDBACK FORM
     */
    // show overlay when clicked on "deactivate"
    superpwa_deactivate_link = $('.wp-admin.plugins-php tr[data-slug="super-progressive-web-apps"] .row-actions .deactivate a');
    superpwa_deactivate_link_url = superpwa_deactivate_link.attr('href');

    superpwa_deactivate_link.click(function (e) {
        e.preventDefault();
        
        // only show feedback form once per 30 days
        var c_value = superpwa_admin_get_cookie("superpwa_hide_deactivate_feedback");

        if (c_value === undefined) {
            $('#superpwa-reloaded-feedback-overlay').show();
        } else {
            // click on the link
            window.location.href = superpwa_deactivate_link_url;
        }
    });
    // show text fields
    $('#superpwa-reloaded-feedback-content input[type="radio"]').click(function () {
        // show text field if there is one
        $(this).parents('li').next('li').children('input[type="text"], textarea').show();
    });
    // send form or close it
    $('#superpwa-reloaded-feedback-content .button').click(function (e) {
        e.preventDefault();
        // set cookie for 30 days
        var exdate = new Date();
        exdate.setSeconds(exdate.getSeconds() + 2592000);
        document.cookie = "superpwa_hide_deactivate_feedback=1; expires=" + exdate.toUTCString() + "; path=/";

        $('#superpwa-reloaded-feedback-overlay').hide();
        if ('superpwa-reloaded-feedback-submit' === this.id) {
            // Send form data
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: 'superpwa_send_feedback',
                    data: $('#superpwa-reloaded-feedback-content form').serialize()
                },
                complete: function (MLHttpRequest, textStatus, errorThrown) {
                    // deactivate the plugin and close the popup
                    $('#superpwa-reloaded-feedback-overlay').remove();
                    window.location.href = superpwa_deactivate_link_url;

                }
            });
        } else {
            $('#superpwa-reloaded-feedback-overlay').remove();
            window.location.href = superpwa_deactivate_link_url;
        }
    });
    // close form without doing anything
    $('.superpwa-for-wp-feedback-not-deactivate').click(function (e) {
        $('#superpwa-reloaded-feedback-overlay').hide();
    });
    
    function superpwa_admin_get_cookie (name) {
	var i, x, y, superpwa_cookies = document.cookie.split( ";" );
	for (i = 0; i < superpwa_cookies.length; i++)
	{
		x = superpwa_cookies[i].substr( 0, superpwa_cookies[i].indexOf( "=" ) );
		y = superpwa_cookies[i].substr( superpwa_cookies[i].indexOf( "=" ) + 1 );
		x = x.replace( /^\s+|\s+$/g, "" );
		if (x === name)
		{
			return unescape( y );
		}
	}
}

}); // document ready