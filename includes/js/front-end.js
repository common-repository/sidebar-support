jQuery(document).ready(function () {
    jQuery("#wp-admin-bar-side_sup_admin_bar li:first-child a").unbind().click(function () {
        //alert('etf');
        console.log('Click Clear Cache Function');

        jQuery.ajax({
            data: {action: "clear_cache_ajax"},
            type: 'POST',
            url: ssAjax.ajaxurl,
            success: function (response) {
                //	jQuery('body').hide();
                console.log('Well Done and got this from sever: ' + response);
                // alert and upon clicking refresh the page
                if (!alert('Cache for all Sidebar Support Feeds cleared!')) {
                    window.location.reload();
                }

                return false;
            }
        }); // end of ajax()
        return false;
    }); // end of form.submit


// This is to show/hide the Settings Options on the front end for Quick Responses
    jQuery(".toggle-custom-textarea-show").unbind().click(function () {
        jQuery('textarea#side-sup-main-wrapper-css-input').slideToggle('fast');
        jQuery('.toggle-custom-textarea-show span').toggle();
        jQuery('.side-sup-custom-css-text').toggle();
    });
    jQuery('.side-sup-settings-toggle').click(function () {
        jQuery(this).next('.side-sup-settings-id-answer').slideToggle('fast');
        return false;
    });


    jQuery("#sidebar-support-submit-front-end").click(function (event) {
        //alert('etf');

        event.preventDefault(); // stop post action
        console.log('Sidebar Support Front End Submit');

        var name = jQuery('#side-sup-quick-response-textarea-set').val();
        var reg = /[^a-zA-Z0-9\@\#\$\-\%\^\*\_\|]+/;
        if (reg.test(name)) {
            alert("Only a-zA-Z0-9!@#$%^*_| Allowed");
            return false;
        }

        if (jQuery('#side-sup-quick-response-textarea-set').val() == '') {
            jQuery('#side-sup-quick-response-textarea-set').val('textarea')
            console.log('Sidebar Support Error');
        }

        jQuery.ajax({
            data: {
                'action': "sidebar_sup_save_front_end_settings",
                'side-sup-quick-response-textarea-set': jQuery('#side-sup-quick-response-textarea-set').val() // or combine serialized form
            },
            type: 'POST',
            url: ssAjax.ajaxurl,
            beforeSend: function () {
                // alert('before')

                jQuery('.sidebar-sup-submit-wrap .sidebar-sup-loader').addClass('fa-spin').css('display', 'inline-block');

            },
            success: function (response) {
                //  alert('success');
                jQuery(".sidebar-sup-loader").hide();
                jQuery('.sidebar-sup-submit-wrap .sidebar-sup-success').css('display', 'inline-block');
                setTimeout("jQuery('.sidebar-sup-success').fadeOut();", 1290);
                console.log('Well Done and got this from sever: ' + response);
                jQuery('#side-sup-quick-response-textarea-set').html(response); //even this code was executed.
            },
            error: function () {
                alert('Error, please contact us at http://slickremix.com/support-forum for help.')
            }
        }); // end of ajax()
        return false;
    }); // end of form.submit

    // Open/Close panel
    jQuery("#side-sup-social-bar-icons-wrap li.toggle, .close-side-sup-panel").click(function () {
        jQuery('li > ul.side-sup-sidebar-menu').not(jQuery(this).children("ul").fadeToggle('fast')).fadeOut();
        jQuery('.side-sup-DisplayBlock').removeClass('side-sup-DisplayBlock').show().fadeOut();
        jQuery('.overflow-wrapper ul').show();
    });
    jQuery("#side-sup-social-bar-icons-wrap li ul").click(function (e) {
        jQuery(this).removeAttr("style").addClass('side-sup-DisplayBlock');
        e.stopPropagation();
    });
    // Close panel with another icon click
    jQuery(".close-side-sup-panel").click(function (e) {
        jQuery('.side-sup-DisplayBlock').fadeOut().removeClass('side-sup-DisplayBlock');
        e.stopPropagation();
    });
    // Close panel with esc key
    jQuery(document).keyup(function (event) {
        if (event.which === 27) {
            jQuery('li > ul').not(jQuery(this).children("ul").fadeToggle('fast')).fadeOut();
            jQuery('.side-sup-DisplayBlock').removeClass('side-sup-DisplayBlock').show().fadeOut();
        }
    });
    // Copy text magic
    jQuery('.side-sup-copy-icon').click(function () {
        var dataID = jQuery(this).attr('data-id');
        // alert(jQuery("#side-sup-menuEdit" + dataID).text());
        var $temp = jQuery("<input>");
        jQuery("body").append($temp);
        $temp.val(jQuery("#side-sup-menuEdit" + dataID).text()).select();
        document.execCommand("copy");
        $temp.remove();

        jQuery(this).find('.sidebar-sup-success').css('display', 'inline-block');
        setTimeout("jQuery('.sidebar-sup-success').fadeOut();", 150);
    });
    // Toggle the Quick Response Settings
    jQuery(".side-sup-sidebar-settings-icon, .front-end-close-button-ss").click(function () {
        jQuery('.quick-response-settings-content').fadeToggle('fast');
    });
    // Users can click a textarea and the ID will be set so users can save the option for later use
    jQuery("textarea").on('click', function () {
        var textareaID = jQuery(this).attr('id');
        jQuery("#side-sup-quick-response-textarea-set").val('#' + textareaID);
        jQuery("#side-sup-quick-response-textarea-id").html('#' + textareaID);
    });
    // Append the Response to the textarea by default if one is not set
    jQuery(".side-sup-menuEdit").click(function () {
        var txt = jQuery.trim(jQuery(this).html());
        var getID = jQuery("#side-sup-quick-response-textarea-id").html();
        var box2 = jQuery(getID)
        box2.val(box2.val() + txt + ' ');
    });
}); // end document ready