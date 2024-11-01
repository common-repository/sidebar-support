jQuery(document).ready(function () {

    jQuery(".side-sup-quick-item").click(function (e) {
        e.stopPropagation();
      //  alert('asdfasdf');
       // jQuery(this).parent().show();
    });

    function side_sup_double_check_function() {
        var x;
        if (confirm("You are about to permanently delete this Topic. The post(s) will not be deleted but moved to the bottom of the page where it says, Items below have no Topics. 'Cancel' to stop, 'OK' to delete.") == true) {
            x = "You pressed OK!";
        } else {
            x = "You pressed Cancel!";
        }
        return x;
    }
    jQuery( "ul.side-sup-ul, ul.quick-item-list li, ul.no-topic-ul-list li").on("click", ".side-sup-delete-category", function(event) {
        event.preventDefault(); // stop post action

        if(side_sup_double_check_function() == 'You pressed Cancel!'){return false};

        var id = jQuery(this).data('id');
        var thisDelete = jQuery(this);
        jQuery.ajax({
            data: {
                'action': "side_sup_delete_topic_ajax",
                // submit our values to function simple_das_fep_add_post
                'id': id
            },
            type: 'POST',
            url: ssAjax.ajaxurl,
            beforeSend: function () {
              //  alert('Are sure you want to do this? You cannot undo this operation.')
                //  jQuery('#new_post .sidebar-sup-submit-wrap').append('<div class="fa fa-cog fa-spin fa-3x fa-fw sidebar-sup-loader"></div>');
                //  jQuery("#new_post .sidebar-sup-success").remove();
            },
            success: function (response) {
                // Complete Sucess
                console.log('Well Done and got this from sever: ' + response);
                jQuery(thisDelete).parents('.side-sup-topic-item').fadeOut();

            },
            error: function () {
                alert('Error, please contact us at http://slickremix.com/support-forum for help.')
            }
        }); // end of ajax()
        return false;
    }); // end of form.submit


    jQuery( "ul.side-sup-ul, ul.quick-item-list li, ul.no-topic-ul-list li").on("click", ".side-sup-delete-item", function(event) {
        event.preventDefault(); // stop post action
        var id = jQuery(this).data('id');
        var nonce = jQuery(this).data('nonce');
        var thisDelete = jQuery(this);
        jQuery.ajax({
            data: {
                'action': "side_sup_delete_quick_item_ajax",
                // submit our values to function simple_das_fep_add_post
                'id': id ,
                'nonce': nonce
            },
            type: 'POST',
            url: ssAjax.ajaxurl,
            beforeSend: function () {
                // alert('before')
              //  jQuery('#new_post .sidebar-sup-submit-wrap').append('<div class="fa fa-cog fa-spin fa-3x fa-fw sidebar-sup-loader"></div>');
              //  jQuery("#new_post .sidebar-sup-success").remove();
            },
            success: function (response) {
                // Complete Sucess
                console.log('Well Done and got this from sever: ' + response);
                jQuery(thisDelete).parents('.side-sup-quick-item').fadeOut();

            },
            error: function () {
                alert('Error, please contact us at http://slickremix.com/support-forum for help.')
            }
        }); // end of ajax()
        return false;
    }); // end of form.submit


    jQuery("#sidebar-support-submit-back-end-quick-links").click(function (event) {
        event.preventDefault(); // stop post action
        console.log('Sidebar Support Back End Submit Click');



        // New Title Input
        if (jQuery('#new_post2 #fep-post-title2').val() == '') {
            jQuery('.quick-links-title').addClass('side-sup-error-input');
        }
        else {
            jQuery('.quick-links-title').removeClass('side-sup-error-input');
        }

        // Select (Topic)Category
        if (jQuery('#new_post2 #ss_qr_topics').val() == '' && jQuery('#new_post2 #newcat2').val() == '') {
            jQuery('.side-sup-select-topics-category-quick-links').addClass('side-sup-error-input');
        }
        else {
            jQuery('.side-sup-select-topics-category-quick-links').removeClass('side-sup-error-input');
        }

        // Link Input
        if (jQuery('#side_support_quick_link').val() == '') {
            jQuery('.side-sup-create-new-quick-links').addClass('side-sup-error-input');
        }
        else {
            jQuery('.side-sup-create-new-quick-links').removeClass('side-sup-error-input');
        }

        // Error Check Scroll to top of page
        if (jQuery('#new_post2 #fep-post-title2').val() == '' || jQuery('#new_post2 #ss_qr_topics').val() == '' && jQuery('#new_post2 #newcat').val() == '' || jQuery('#new_post2 #side_sup_topic_placement_links').val() == '') {

            // Animate the scrolling motion.
            jQuery("body, html").animate({
                scrollTop: 0
            }, "fast");
            return false;
        }

        jQuery.ajax({
            data: {
                'action': "side_sup_add_quick_link_ajax",
                // submit our values to function simple_das_fep_add_post
                'post_title2': jQuery('#new_post2 #fep-post-title2').val(),
                'ss_qr_topics': jQuery('#new_post2 #ss_qr_topics').val(),
                'newcat2': jQuery('#new_post2 #newcat2').val(),
                'side_support_quick_link': jQuery('#new_post2 #side_support_quick_link').val(),
                'side_sup_quick_link_target': jQuery('#new_post2 #side_sup_quick_link_target').val(),
                'side_sup_topic_placement_links': jQuery('#new_post2 #side_sup_topic_placement_links').val(),
            },
            type: 'POST',
            url: ssAjax.ajaxurl,
            beforeSend: function () {
                // alert('before')
                jQuery('#new_post2 .sidebar-sup-submit-wrap').append('<div class="fa fa-cog fa-spin fa-3x fa-fw sidebar-sup-loader"></div>');
                jQuery("#new_post2 .sidebar-sup-success").remove();
            },
            success: function (response) {
                //  serverside double check it's working
                if (response == 'You must add a title.') {
                    jQuery("#fep-post-title2").focus();
                    jQuery("#new_post2 .sidebar-sup-loader").remove();
                    alert(response);
                    console.log(response);
                    return false;
                }
                if (response == 'You must select an existing Topic or Create a new Topic.') {
                    jQuery("#new_post2 #ss_qr_topics").focus();
                    jQuery("#new_post2 .sidebar-sup-loader").remove();
                    alert(response);
                    console.log(response);
                    return false;
                }
                if (response == 'That Topic already exists.') {
                    jQuery("#newcat2").focus();
                    jQuery("#new_post2 .sidebar-sup-loader").remove();
                    alert(response);
                    console.log(response);
                    return false;
                }
                if (response == 'You must add a Quick Link.') {
                    jQuery("#side_support_quick_link").focus();
                    jQuery("#new_post2 .sidebar-sup-loader").remove();
                    alert(response);
                    console.log(response);
                    return false;
                }
                if (response == 'Please enter a Valid URL') {
                    jQuery("#side_support_quick_link").focus();
                    jQuery("#new_post2 .sidebar-sup-loader").remove();
                    alert(response + ' for example http://website.com');
                    console.log(response);
                    return false;
                }

                // Complete Sucess
                console.log('Well Done and got this from sever: ' + response);
                jQuery("#new_post2 .sidebar-sup-loader").remove();
                jQuery('#new_post2 .sidebar-sup-submit-wrap').append('<div class="fa fa-check-circle fa-3x fa-fw sidebar-sup-success"></div>');

               // jQuery('ul.quick_links_sortable').nestedSortable('refresh');
               // jQuery('ul.quick_responses_sortable').nestedSortable('refresh');

                if(jQuery('#newcat2').val() != ''){
                    jQuery('.side-sup-select-topics-category').show();
                    var str = jQuery('#newcat2').val();
                    str = str.replace(/\s+/g, '-').toLowerCase();
                    console.log(str); // "my-converted-name-for-wordpress"
                    jQuery("#new_post2 #ss_qr_topics").append('<option value="' + str + '">' + jQuery('#newcat2').val() + '</option>');
                }

                jQuery('#fep-post-title2').val('');
                jQuery('#fep-post-text2').val('');
                jQuery('#new_post2 #ss_qr_topics').val('');
                jQuery('#new_post2 #side_support_quick_link').val('');
                jQuery('#newcat2').val('');
                setTimeout("jQuery('#new_post2 .sidebar-sup-success').fadeOut();", 4000);

                var response = jQuery(response);

                var dataTopicID = getTopicID = response.filter('.quick_item_ajax').attr('data-topic');
                var dataName = 'li[data-id="' + dataTopicID + '"] ul.quick-item-list';

                // We check to see if the Topic exists on the page already and if so append it to that Topic on the page
                if( dataTopicID != undefined && response.filter('.quick_item_ajax').attr('data-topic') == dataTopicID ){
                    // alert(response.filter('.quick_item_ajax').attr('data-topic'));
                    // alert(dataName)
                    jQuery(dataName).append(response);
                }
                else if(jQuery("#no-topic-quick-items").length) {
                    // alert('Topic Exist');
                    jQuery(response).insertBefore('.quick_links_sortable #no-topic-quick-items');
                }
                else {
                    // alert('no topics yet');
                  //  jQuery('.ss-create-quick-response').hide();
                    jQuery('.side-sup-error-notice').hide();
                    jQuery('ul.side-sup-ul').append(response);
                }
            },
            error: function () {
                alert('Error, please contact us at http://slickremix.com/support-forum for help.')
            }
        }); // end of ajax()
        return false;
    }); // end of form.submit


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


    function get_tinymce_content(id) {
        var content;
        var inputid = 'editpost';
        var editor = tinyMCE.get(inputid);
        var textArea = jQuery('textarea#' + inputid);
        if (textArea.length>0 && textArea.is(':visible')) {
            content = textArea.val();
            if(content == null){ return false;}
        } else {
            content = editor.getContent();
        }
        return content;
    }

    jQuery("#sidebar-support-submit-back-end").click(function (event) {
        event.preventDefault(); // stop post action
        console.log('Sidebar Support Back End Submit Click');
        console.log(jQuery('#quick_response_show_title').val());

        // New Title Input
        if (jQuery('#fep-post-title').val() == '') {
            jQuery('.fep-post-title').addClass('side-sup-error-input');
        }
        else {
            jQuery('.fep-post-title').removeClass('side-sup-error-input');
        }

        // Select (Topic)Category
        if (jQuery('#ss_qr_topics').val() == '' && jQuery('#newcat').val() == '') {
            jQuery('.side-sup-select-topics-category').addClass('side-sup-error-input');
        }
        else {
            jQuery('.side-sup-select-topics-category').removeClass('side-sup-error-input');
        }

        // New (Topic)Category Input
        if (jQuery('#newcat').val() == '' && jQuery('#ss_qr_topics').val() == null) {
            jQuery('.side-sup-create-new-topics-category').addClass('side-sup-error-input');
        }
        else {
            jQuery('.side-sup-create-new-topics-category').removeClass('side-sup-error-input');
        }
        // Check wp_editor textareas
        if (jQuery('#wp-editpost-wrap').hasClass('html-active') == true && jQuery('#editpost').val() == '' || jQuery('#wp-editpost-wrap').hasClass('tmce-active') == true && tinyMCE.get('editpost').getContent() == '') {
            jQuery('#wp-editpost-wrap').addClass('side-sup-error-input');
        } else {
            // The active tab is HTML, so just query the textarea
            jQuery('#wp-editpost-wrap').removeClass('side-sup-error-input');
        }

       // alert(jQuery('#editpost').val())

        // Error Check Scroll to top of page
        if (jQuery('#fep-post-title').val() == '' || jQuery('#ss_qr_topics').val() == '' && jQuery('#newcat').val() == '' || jQuery('#wp-editpost-wrap').hasClass('html-active') == true && jQuery('#editpost').val() == '' || jQuery('#wp-editpost-wrap').hasClass('tmce-active') == true && tinyMCE.get('editpost').getContent() == '') {

            // Animate the scrolling motion.
            jQuery("body, html").animate({
                scrollTop: 0
            }, "fast");
            return false;
        }

        jQuery.ajax({
            data: {
                'action': "side_sup_add_quick_response_ajax",
                // submit our values to function simple_das_fep_add_post
                'post_title': jQuery('#new_post #fep-post-title').val(),
                'editpost': get_tinymce_content(),
                'ss_qr_topics': jQuery('#new_post #ss_qr_topics').val(),
                'newcat': jQuery('#new_post #newcat').val(),
                'side_sup_topic_placement_responses': jQuery('#new_post #side_sup_topic_placement_responses').val(),
                'quick_response_show_title': jQuery('#quick_response_show_title').val(),

            },
            type: 'POST',
            url: ssAjax.ajaxurl,
            beforeSend: function () {
                // alert('before')
                jQuery('#new_post .sidebar-sup-submit-wrap').append('<div class="fa fa-cog fa-spin fa-3x fa-fw sidebar-sup-loader"></div>');
                jQuery("#new_post .sidebar-sup-success").remove();
            },
            success: function (response) {
                //  serverside double check it's working
                if (response == 'You must add a title.') {
                    jQuery("#fep-post-title").focus();
                    jQuery("#new_post .sidebar-sup-loader").remove();
                    alert(response);
                    console.log(response);
                    return false;
                }
                if (response == 'You must select an existing Topic or Create a new Topic.') {
                    jQuery("#new_post #ss_qr_topics").focus();
                    jQuery("#new_post .sidebar-sup-loader").remove();
                    alert(response);
                    console.log(response);
                    return false;
                }
                if (response == 'That Topic already exists.') {
                    jQuery("#new_post #newcat").focus();
                    jQuery("#new_post .sidebar-sup-loader").remove();
                    alert(response);
                    console.log(response);
                    return false;
                }
                if (response == 'You must add a Quick Response.') {
                    jQuery("#new_post .sidebar-sup-loader").remove();
                    alert(response);
                    console.log(response);
                    return false;
                }
                // Complete Sucess
                console.log('Well Done and got this from sever: ' + response);
                jQuery("#new_post .sidebar-sup-loader").remove();
                jQuery('#new_post .sidebar-sup-submit-wrap').append('<div class="fa fa-check-circle fa-3x fa-fw sidebar-sup-success" ></div>');

               // jQuery('ul.quick_responses_sortable').nestedSortable();
               // jQuery('ul.quick_responses_sortable').trigger("sortupdate");

                if(jQuery('#newcat').val() != ''){
                    jQuery('.side-sup-select-topics-category').show();
                    var str = jQuery('#newcat').val();
                    str = str.replace(/\s+/g, '-').toLowerCase();
                    console.log(str); // "my-converted-name-for-wordpress"
                     jQuery("#new_post #ss_qr_topics").append('<option value="' + str + '">' + jQuery('#newcat').val() + '</option>');
                }

                jQuery('#new_post #fep-post-title').val('');
                jQuery('#new_post #fep-post-text').val('');
                jQuery('#new_post #ss_qr_topics').val('');
                jQuery('#new_post #newcat').val('');


                var editor = tinyMCE.get('editpost');
                if (editor !== null) {
                    tinyMCE.get('editpost').setContent('');
                }
                jQuery('#editpost').val('');
                setTimeout("jQuery('#new_post .sidebar-sup-success').fadeOut();", 4000);

                var response = jQuery(response);

                var dataTopicID = getTopicID = response.filter('.quick_item_ajax').attr('data-topic');
                var dataName = 'li[data-id="' + dataTopicID + '"] ul.quick-item-list';

                // We check to see if the Topic exists on the page already and if so append it to that Topic on the page
                if( dataTopicID != undefined && response.filter('.quick_item_ajax').attr('data-topic') == dataTopicID ){
                    // alert(response.filter('.quick_item_ajax').attr('data-topic'));
                    // alert(dataName)
                    jQuery(dataName).append(response);
                }
                else if(jQuery("#no-topic-quick-items").length) {
                    // alert('Topic Exist');
                    jQuery(response).insertBefore('.quick_responses_sortable #no-topic-quick-items');
                }
                else {
                    // alert('no topics yet');
                    jQuery('.side-sup-error-notice').hide();
                    jQuery('ul.side-sup-ul').append(response);
                }
            },
            error: function () {
                alert('Error, please contact us at http://slickremix.com/support-forum for help.')
            }
        }); // end of ajax()
        return false;
    }); // end of form.submit

    jQuery('.side-sup-color-options-open-close-all').click(function () {
        jQuery('.side-sup-ct-color-options-wrap .side-sup-settings-id-answer').slideToggle('fast');
        return false;
    });

    jQuery('.expandEditor').attr('title', 'Click to show/hide item editor');
    jQuery('.deleteMenu').attr('title', 'Click to delete item.');


    jQuery('.deleteMenu').click(function () {
        var id = jQuery(this).attr('data-id');
        jQuery('#side-sup-menuItem_' + id).remove();
    });

    // Open/close handles on the Topics and children li's with handles.
    jQuery("ul.side-sup-ul, ul.quick-item-list li, ul.no-topic-ul-list li").on("click", "span.expandEditor", function () {

        var id = jQuery(this).attr('data-id');
        jQuery('#side-sup-menuItem_' + id + ' ul.quick-item-list').toggle();
        jQuery('#side-sup-menuEdit' + id).toggle();

        //  alert('#side-sup-menuItem_'+id + ' ul.quick-item-list');
        jQuery(this).toggleClass('ui-icon-triangle-1-n').toggleClass('ui-icon-triangle-1-s');

    });

}); // close document ready

