<?php namespace Sidebar_Support;
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Sidebar_Builder {
    function __construct() {
        if (is_admin()) {
            //  add_action('init', array($this,'simple_das_fep_add_post'));

            add_action('admin_enqueue_scripts', array($this, 'add_sidebar_builder_scripts_styles'));
            add_action('admin_menu', array($this, 'add_sidebar_builder_submenu'));
            //Ajax for Sidebar Builder
            add_action('wp_ajax_side_sup_item_sort', array($this, 'side_sup_save_item_order'));
            add_action('wp_ajax_nopriv_side_sup_item_sort', array($this, 'side_sup_save_item_order'));

            //Add Ajax
            add_action('wp_ajax_side_sup_add_quick_response_ajax', array($this, 'side_sup_add_quick_response_ajax'));
            add_action('wp_ajax_side_sup_add_quick_link_ajax', array($this, 'side_sup_add_quick_link_ajax'));
            //Delete Ajax
            add_action('wp_ajax_side_sup_delete_quick_item_ajax', array($this, 'side_sup_delete_quick_item_ajax'));
            add_action('wp_ajax_side_sup_delete_topic_ajax', array($this, 'side_sup_delete_topic_ajax'));
        }
    }

    /**
     * Add Sidebar Builder Scripts Styles
     *
     * @since 1.0.0
     */
    function add_sidebar_builder_scripts_styles() {
        if (isset($_GET['page']) && $_GET['page'] == 'side-sup-sidebar-builder-page') {

            wp_register_style('Side-Sup-Sidebar-Builder-CSS', plugins_url('css/admin-pages.css', __FILE__));
            wp_enqueue_style('Side-Sup-Sidebar-Builder-CSS');

            wp_register_style('font-awesome', plugins_url('icon-picker/dist/css/font-awesome.min.css', __FILE__));
            wp_enqueue_style('font-awesome');

            wp_register_style('font-awesome-iconpicker', plugins_url('icon-picker/dist/css/fontawesome-iconpicker.min.css', __FILE__));
            wp_enqueue_style('font-awesome-iconpicker');

            wp_register_script('fontawesome-iconpicker-js', plugins_url('icon-picker/dist/js/fontawesome-iconpicker.min.js', __FILE__));
            wp_enqueue_script('fontawesome-iconpicker-js');

            if (is_plugin_active('sidebar-support-premium/sidebar-support-premium.php')) {
                wp_register_script('jquery-nested-sortable', plugins_url('js/jquery.mjs.nestedSortable.js', __FILE__), array('jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-sortable'));
                wp_enqueue_script('jquery-nested-sortable');

                wp_register_script('Side-Sup-Sidebar-Builder', plugins_url('../../sidebar-support-premium/admin/js/sidebar-builder.js', __FILE__), array('jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-sortable'));
                wp_enqueue_script('Side-Sup-Sidebar-Builder');
            }

        }
    }

    /**
     * Add Sidebar Builder Submenu
     *
     * @since 1.0.0
     */
    function add_sidebar_builder_submenu() {
        add_submenu_page(
            'edit.php?post_type=ss_quick_responses',
            __('Sidebar Builder', 'sidebar-suppport'),
            __('Sidebar Builder', 'sidebar-suppport'),
            'manage_options',
            'side-sup-sidebar-builder-page',
            array($this, 'side_sup_sidebar_builder')
        );
    }

    /**
     * Side Support Sidebar Builder
     *
     * @since 1.0.0
     */
    function side_sup_sidebar_builder() {
        $display = new Display_List();
        $topics_class = new Topics();

        //Quick Responses
        $side_sup_show_responses_list_closed = get_option('side-sup-show-responses-list-closed') ? get_option('side-sup-show-responses-list-closed') : 'open';
        $side_sup_show_links_list_closed = get_option('side-sup-show-links-list-closed') ? get_option('side-sup-show-links-list-closed') : 'open';

        if ($side_sup_show_responses_list_closed == 'closed') {
            ?>

            <style>
                #tab-content1 ul.side-sup-ul ul.quick-item-list {
                    display: none;
                }
            </style> <?php }
        if ($side_sup_show_links_list_closed == 'closed') {
            ?>
            <style>
                #tab-content2 ul.side-sup-ul ul.quick-item-list {
                    display: none;
                }
            </style>
        <?php } ?>
        <style>
            #tab-content1 .iconpicker-component {
                color: <?php echo get_option('side-sup-quick-response-icon-color'); ?> !important;
                background: <?php echo get_option('side-sup-quick-response-background-color'); ?> !important;
            }

            #tab-content2 .iconpicker-component {
                color: <?php echo get_option('side-sup-quick-links-icon-color'); ?> !important;
                background: <?php echo get_option('side-sup-quick-links-background-color'); ?> !important;
            }
        </style>

        <div class="side-sup-ez-life-maker-wrap">
            <div id="new_post_wrap">
                <form id="new_post" name="new_post" method="post" action="">
                    <h1><?php _e('Quick Response Form', 'sidebar-support'); ?></h1>
                    <p class="side-sup-form-instructions"><?php _e('<strong>1st:</strong> Add a new Topic name or choose from an existing one if it has been created already. <strong>2nd:</strong> Add the Title for your Quick Response. <strong>3rd:</strong>  Choose to show the Title or not on the front end. <strong>4th:</strong>  Add your Quick Response. <strong>5th:</strong> Submit the form and your new quick response will appear below.', 'sidebar-support'); ?></p>

                    <p class="side-sup-create-new-topics-category">
                        <label><?php _e('Create New Topic Name', 'sidebar-support'); ?></label><br/>
                        <input type="text" name="newcat" id="newcat" value=""/><br/>
                        <small><?php _e('This will be the Main Title above your responses.', 'sidebar-support'); ?></small>
                    </p>

                    <?php if (!isset($_GET['tab']) || isset($_GET['tab']) && $_GET['tab'] == 'quick_responses') {
                        $topics_class->topic_by_list_type_dropdown('quick_responses');
                    } ?>


                    <p class="fep-post-title">
                        <label><?php _e('Quick Response Title', 'sidebar-support'); ?></label><br/>
                        <input type="text" id="fep-post-title" name="post_title" placeholder="" value=""/>
                    </p>

                    <p class="side-sup-create-new-response-option">
                        <label><?php _e('Show Quick Response Title on Front End', 'sidebar-support'); ?></label><br/>
                        <select id="quick_response_show_title" name="quick_response_show_title" class="medium-text">
                            <option value="yes"><?php _e('Yes, Show Title', 'sidebar-support'); ?></option>
                            <option value="no"><?php _e('No, Hide Title', 'sidebar-support'); ?></option>
                        </select>
                    </p>
                    <p class="side-sup-enter-content-media-etc">
                        <label><?php _e('Quick Response', 'sidebar-support'); ?></label><br/>
                        <textarea class="fep-content" name="posttext" id="fep-post-text" rows="4" cols="60"
                                  style="display:none"></textarea>

                        <?php
                        $content = '';
                        $editor_id = 'editpost';
                        wp_editor($content, $editor_id, array(
                            'textarea_rows' => '8',
                            'drag_drop_upload' => true,
                        ));

                        ?>
                    <div class="sidebar-sup-submit-wrap" id="side-sup-submit-post-form">
                        <div id="sidebar-support-submit-back-end" class="">
                            <?php _e('Submit Form', 'sidebar-support'); ?>
                        </div>
                        <div class="close-quick-responses-box ss-create-quick-response">
                            <?php _e('Close', 'sidebar-support'); ?>
                        </div>
                    </div>
                    <!-- This add the proper response topic -->
                    <input type="hidden" name="side_sup_topic_placement_responses"
                           id="side_sup_topic_placement_responses" class="side-sup-settings-admin-input" type="checkbox"
                           checked="checked" value="1">
                    <input type="hidden" name="action" value="post"/>
                    <input type="hidden" name="empty-description" id="empty-description" value="1"/>

                    <div
                        class="side-sup-message-to-refresh"><?php _e('Click Here to Refresh the page if you want to see your item in the list below.', 'sidebar-support'); ?></div>
                    <?php wp_nonce_field('new-post'); ?>
                </form>
            </div>

            <div id="new_post_wrap2">
                <form id="new_post2" name="new_post2" method="post" action="">
                    <h1><?php _e('Quick Links Form', 'sidebar-support'); ?></h1>
                    <p class="side-sup-form-instructions"><?php _e('<strong>1st:</strong> Add the Title for your Quick Link. <strong>2nd:</strong> Add a new Topic name or choose from an existing one if it has been created already. <strong>3rd:</strong> Add your Quick Link  <strong>4th:</strong> Choose for the Link to open in a new window or not. <strong>5th:</strong> Submit the form and your new Quick Link will appear below.', 'sidebar-support'); ?></p>

                    <p class="side-sup-create-new-topics-category-quick-links">
                        <label><?php _e('Create New Topic Name', 'sidebar-support'); ?></label><br/>
                        <input type="text" name="newcat2" id="newcat2" value=""/><br/>
                        <small><?php _e('This will be the Main Title above your links.', 'sidebar-support'); ?></small>
                    </p>

                    <?php if (isset($_GET['tab']) && $_GET['tab'] == 'quick_links') {
                        $topics_class->topic_by_list_type_dropdown('quick_links');
                    } ?>

                    <p class="quick-links-title">
                        <label><?php _e('Quick Link Title', 'sidebar-support'); ?></label><br/>
                        <input type="text" id="fep-post-title2" name="post_title2" placeholder=""
                               value=""/>
                    </p>

                    <p class="side-sup-create-new-response-option" style="display: none;">
                        <label><?php _e('Hide Quick Link Title on Front End', 'sidebar-support'); ?></label><br/>
                        <select id="side_sup_quick_link_target" name="side_sup_quick_link_target" class="medium-text">
                            <option value="1" selected="selected">No, Show Title</option>
                            <option value="0">Yes, Hide Title</option>
                        </select>
                    </p>

                    <p class="side-sup-create-new-quick-links">
                        <label><?php _e('Quick Link', 'sidebar-support'); ?></label><br/>
                        <input type="text" name="side_support_quick_link" id="side_support_quick_link" value=""/>
                        <small><?php _e('Example: http://www.myWebsite.com', 'sidebar-support'); ?></small>
                        <br/>
                    </p>

                    <p class="side-sup-create-new-link-option">
                        <label><?php _e('Link Option', 'sidebar-support'); ?></label><br/>
                        <select id="side_sup_quick_link_target" name="side_sup_quick_link_target" class="medium-text">
                            <option value="_self" selected="selected">Same Window</option>
                            <option value="_blank">New Window</option>
                        </select>
                    </p>
                    <div class="sidebar-sup-submit-wrap" id="side-sup-submit-post-form2">
                        <div id="sidebar-support-submit-back-end-quick-links" class="">
                            <?php _e('Submit Form', 'sidebar-support'); ?>
                        </div>
                        <div class="close-quick-responses-box ss-create-quick-links">
                            <?php _e('Close', 'sidebar-support'); ?>
                        </div>
                    </div>

                    <input type="hidden" name="side_sup_topic_placement_links" class="side-sup-settings-admin-input"
                           id="side_sup_topic_placement_links" type="checkbox" checked="checked" value="1">
                    <input type="hidden" name="action2" value="post"/>
                    <input type="hidden" name="empty-description2" id="empty-description2" value="1"/>
                    <?php wp_nonce_field('new-post2'); ?>
                </form>
            </div>

        </div>

        <form method="post" class="side-sup-settings-admin-form" action="options.php">

            <?php settings_fields('side-sup-builder-settings');

            $side_sup_quick_resp_icon = get_option('side-sup-quick-resp-icon') ? get_option('side-sup-quick-resp-icon') : 'fa-support';
            $side_sup_quick_link_icon = get_option('side-sup-quick-link-icon') ? get_option('side-sup-quick-link-icon') : 'fa-link';
            $side_sup_menu1 = get_option('side-sup-menu1') ? get_option('side-sup-menu1') : '';
            $side_sup_menu2 = get_option('side-sup-menu2') ? get_option('side-sup-menu2') : '';
            $side_sup_menu3 = get_option('side-sup-menu3') ? get_option('side-sup-menu3') : '';
            if (is_plugin_active('sidebar-support-premium/sidebar-support-premium.php')) {
                $side_sup_menu4 = get_option('side-sup-menu4') ? get_option('side-sup-menu4') : '';
                $side_sup_menu5 = get_option('side-sup-menu5') ? get_option('side-sup-menu5') : '';
            }
            $side_sup_plugin_themes_option = get_option('side-sup-plugin-themes-option') ? get_option('side-sup-plugin-themes-option') : '';
            ?>
            <div class="quick_docs_list_wrap side-sup-list">

                <div class="tabs" id="tabs">

                    <label for="tab1"
                           class="tab1 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'quick_responses') {
                               echo 'tab-active';
                           } elseif (!isset($_GET['tab'])) {
                               echo 'tab-active';
                           } ?>" id="quick_responses">
                        <i class="fa <?php echo $side_sup_quick_resp_icon ?>"></i>
                        <span class="ss-text"><?php _e('', 'sidebar-support') ?></span>
                    </label>

                    <label for="tab2"
                           class="tab2 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'quick_links') {
                               echo ' tab-active';
                           } ?>" id="quick_links">
                        <i class="fa <?php echo $side_sup_quick_link_icon ?>"></i>
                        <span class="ss-text"><?php _e('', 'sidebar-support') ?></span>
                    </label>

                    <label for="tab3" class="tab3 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'wordpress') {
                        echo ' tab-active';
                    } ?>" id="wordpress">
                        <span id="wordpress-icon"></span>
                        <span class="ss-text"><?php _e('', 'sidebar-support') ?></span>
                    </label>

                    <label for="tab4" class="tab4 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'github') {
                        echo 'tab-active';
                    } ?>" id="github">
                        <span id="github-icon"></span>
                        <span class="ss-text"><?php _e('', 'sidebar-support') ?></span>
                    </label>

                    <label for="tab5" class="tab5 tabbed <?php if (isset($_GET['tab']) && $_GET['tab'] == 'bitbucket') {
                        echo 'tab-active';
                    } ?>" id="bitbucket">
                        <span id="bitbucket-icon"></span>
                        <span class="ss-text"><?php _e('', 'sidebar-support') ?></span>
                    </label>

                    <div id="tab-content1"
                         class="tab-content side-sup-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'quick_responses' || !isset($_GET['tab'])) {
                             echo ' pane-active';
                         } ?>">
                        <section>
                            <h3><?php _e('Quick Responses Options', 'sidebar-support') ?></h3>
                            <p>
                                <?php _e('Set the option to show this menu on the front end for Admins only or Everyone.', 'sidebar-support') ?>
                            </p>

                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e("Menu Option", "sidebar-support"); ?></div>
                                <select name="side-sup-menu1" class="side-sup-settings-admin-input" id="side-sup-menu1">
                                    <option value="off"><?php _e('Menu OFF', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu1, 'admins', false) ?>
                                        value="admins"><?php _e('Menu ON for Admins', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu1, 'everyone', false) ?>
                                        value="everyone"><?php _e('Menu ON for Everyone', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap ss-icon-float-margin-top">
                                <div
                                    class="side-sup-admin-input-label ss-icon-picker-float"><?php _e("Icon Picker", "sidebar-support"); ?></div>
                                <i class="fa-3x picker-target ss-hidden"></i>
                                <script>
                                    jQuery(document).on('click', "#tab-content1 .iconpicker-item", function () {
                                        var liId = jQuery(this).find(".fa").attr("class");
                                        jQuery('#side-sup-quick-resp-icon').val(liId);
                                        // alert(liId);
                                    });
                                    jQuery(document).on('click', "#tab-content2 .iconpicker-item", function () {
                                        var liId = jQuery(this).find(".fa").attr("class");
                                        jQuery('#side-sup-quick-link-icon').val(liId);
                                        //  alert(liId);
                                    });
                                </script>
                                <input class="ss-hidden form-control icp icp-opts" id="side-sup-quick-resp-icon"
                                       name="side-sup-quick-resp-icon" value="<?php echo $side_sup_quick_resp_icon ?>"
                                       type="text"/>

                                <div class="input-group action-create ss-picker ss-icon-picker-float">
                                    <div class="input-group-addon ss-picker-position">
                                        <i class="<?php echo $side_sup_quick_resp_icon ?>"></i>
                                    </div>
                                    <input data-placement="bottomLeft"
                                           class="form-control icp icp-auto ss-picker-icon-icp"
                                           value="git-hub<?php echo $side_sup_quick_resp_icon ?>" type="text"/>
                                </div>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap ss-margin-bottom">
                                <div
                                    class="side-sup-admin-input-label"><?php _e("Admin Option", "sidebar-support"); ?></div>
                                <select name="side-sup-show-responses-list-closed" class="side-sup-settings-admin-input"
                                        id="side-sup-show-responses-list-closed">
                                    <option <?php echo selected($side_sup_show_responses_list_closed, 'open', false) ?>
                                        value="open"><?php _e('Display Topics Open below', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_show_responses_list_closed, 'closed', false) ?>
                                        value="closed"><?php _e('Display Topics Closed below', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <input type="submit" class="button button-primary ss-float-left"
                                   value="<?php _e('Save All Changes'); ?>"/>

                        </section>
                        <div class="clear"></div>

                        <h3><?php _e('Quick Responses', 'sidebar-support') ?></h3>
                        <p>
                            <?php _e('Below are your Quick Responses. Create a new Quick Response by clicking the button below.', 'sidebar-support');
                            if (!is_plugin_active('sidebar-support-premium/sidebar-support-premium.php')) {
                                ?>
                                <br/>
                                <?php
                                _e('With the <strong><a href="http://www.slickremix.com/downloads/sidebar-support-premium-extension/" target="_blank">Premium Extension</a></strong> you can sort the order of the Responses and Topics by dragging them into place and more. ', 'sidebar-support');
                            } ?>
                        </p>
                        <input type="submit" class="button button-secondary ss-create-quick-response ss-float-left"
                               value="<?php _e('Create New Quick Response'); ?>"/>

                        <a href="edit.php?post_status=trash&post_type=ss_quick_responses"
                           class="button button-secondary ss-float-right"><?php _e('Trash'); ?></a>
                        <a href="edit.php?post_type=ss_quick_responses"
                           class="button button-secondary ss-float-right"><?php _e('Published'); ?></a>

                        <div class="clear"></div>
                        <?php if (isset($_GET['tab']) && $_GET['tab'] == 'quick_responses' || !isset($_GET['tab'])) {
                            echo $display->display_the_list('quick_responses', 'yes');
                        } ?>

                    </div> <!-- #tab-content1 -->

                    <div id="tab-content2"
                         class="tab-content side-sup-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'quick_links') {
                             echo ' pane-active';
                         } ?>">
                        <section>
                            <h3><?php _e('Quick Links Options', 'sidebar-support') ?></h3>
                            <p>
                                <?php _e('Set the option to show this menu on the front end for Admins only or Everyone.', 'sidebar-support') ?>
                            </p>
                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e("Menu Option", "sidebar-support"); ?></div>
                                <select name="side-sup-menu2" class="side-sup-settings-admin-input" id="side-sup-menu2">
                                    <option value="off"><?php _e('Menu OFF', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu2, 'admins', false) ?>
                                        value="admins"><?php _e('Menu ON for Admins', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu2, 'everyone', false) ?>
                                        value="everyone"><?php _e('Menu ON for Everyone', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap ss-icon-float-margin-top">
                                <div
                                    class="side-sup-admin-input-label ss-icon-picker-float"><?php _e("Icon Picker", "sidebar-support"); ?></div>
                                <i class="fa-3x picker-target ss-hidden"></i>
                                <input class="form-control icp icp-opts ss-hidden" id="side-sup-quick-link-icon"
                                       name="side-sup-quick-link-icon" value="<?php echo $side_sup_quick_link_icon ?>"
                                       type="text"/>

                                <div class="input-group action-create ss-picker ss-icon-picker-float">
                                    <div class="input-group-addon ss-picker-position">
                                        <i class="<?php echo $side_sup_quick_link_icon ?>"></i>
                                    </div>
                                    <input data-placement="bottomLeft"
                                           class="form-control icp icp-auto ss-picker-icon-icp"
                                           value="<?php echo $side_sup_quick_link_icon ?>" type="text"/>
                                </div>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap ss-margin-bottom">
                                <div
                                    class="side-sup-admin-input-label"><?php _e("Admin Option", "sidebar-support"); ?></div>
                                <select name="side-sup-show-links-list-closed" class="side-sup-settings-admin-input"
                                        id="side-sup-show-links-list-closed">
                                    <option <?php echo selected($side_sup_show_links_list_closed, 'open', false) ?>
                                        value="open"><?php _e('Display Topics Open', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_show_links_list_closed, 'closed', false) ?>
                                        value="closed"><?php _e('Display Topics Closed', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <input type="submit" class="button button-primary ss-float-left"
                                   value="<?php _e('Save All Changes'); ?>"/>
                        </section>
                        <div class="clear"></div>

                        <h3><?php _e('Quick Links', 'sidebar-support') ?></h3>
                        <p>
                            <?php _e('Below are your Quick Links. Create a new Quick Link by clicking the button below.', 'sidebar-support');
                            if (!is_plugin_active('sidebar-support-premium/sidebar-support-premium.php')) {
                                ?>
                                <br/>
                                <?php
                                _e('With the <strong><a href="http://www.slickremix.com/downloads/sidebar-support-premium-extension/" target="_blank">Premium Extension</a></strong> you can sort the order of the Links and Topics by dragging them into place and more. ', 'sidebar-support');
                            } ?>
                        </p>
                        <input type="submit" class="button button-secondary ss-create-quick-links ss-float-left"
                               value="<?php _e('Create New Quick Link'); ?>"/>
                        <a href="edit.php?post_status=trash&post_type=ss_quick_links"
                           class="button button-secondary ss-float-right"><?php _e('Trash'); ?></a>
                        <a href="edit.php?post_type=ss_quick_links"
                           class="button button-secondary ss-float-right"><?php _e('Published'); ?></a>

                        <div class="clear"></div>
                        <?php if (isset($_GET['tab']) && $_GET['tab'] == 'quick_links') {
                            echo $display->display_the_list('quick_links', 'yes');
                        } ?>
                    </div> <!-- #tab-content2 -->

                    <div id="tab-content3"
                         class="tab-content side-sup-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'wordpress') {
                             echo ' pane-active';
                         } ?>">
                        <?php
                        $side_sup_wordpress_username = get_option('side-sup-wordpress-username');
                        ?>
                        <section>
                            <h3><?php _e('Wordpress', 'sidebar-support') ?></h3>
                            <p>
                                <?php _e('This will show list of all the plugins your have developed so you can easily click to view stats, reviews and more.<br/>Simply add your Wordpress Username to show the feed.', 'sidebar-support') ?>
                            </p>

                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e("Menu Option", "sidebar-support"); ?></div>
                                <select name="side-sup-menu3" class="side-sup-settings-admin-input" id="side-sup-menu3">
                                    <option value="off"><?php _e('Menu OFF', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu3, 'admins', false) ?>
                                        value="admins"><?php _e('Menu ON for Admins', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu3, 'everyone', false) ?>
                                        value="everyone"><?php _e('Menu ON for Everyone', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div class="side-sup-admin-input-label"><?php _e('Username', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" name="side-sup-wordpress-username" id="side-sup-wordpress-username"
                                       class="feed-them-social-admin-input"
                                       value="<?php echo $side_sup_wordpress_username ?>" placeholder=""/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e("Display List", "sidebar-support"); ?></div>
                                <select name="side-sup-plugin-themes-option" class="side-sup-settings-admin-input"
                                        id="side-sup-plugin-themes-option">
                                    <option <?php echo selected($side_sup_plugin_themes_option, 'plugins', false) ?>
                                        value="plugins"><?php _e('Show Plugin List', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_plugin_themes_option, 'themes', false) ?>
                                        value="themes"><?php _e('Show Themes List', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_plugin_themes_option, 'plugins_themes', false) ?>
                                        value="plugins_themes"><?php _e('Show Plugins and Themes List', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap" style="display: none">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Shortcode Option', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" class="feed-them-social-admin-input"
                                       value="<?php echo '[shortcode here]' ?>" placeholder="" onclick="this.select()"/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                        </section>
                        <input type="submit" class="button button-primary ss-float-left"
                               value="<?php _e('Save All Changes'); ?>"/>
                    </div> <!-- #tab-content3 -->

                    <div id="tab-content4"
                         class="tab-content side-sup-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'github') {
                             echo ' pane-active';
                         } ?>">
                        <?php
                        $side_sup_type_option = get_option('side-sup-github-user-type');
                        $side_sup_sort_option = get_option('side-sup-github-user-sort');
                        $side_sup_direction_option = get_option('side-sup-github-user-direction');
                        $side_sup_github_org_name = get_option('side-sup-github-org-name');
                        $side_sup_github_org_type = get_option('side-sup-github-org-type');
                        $side_sup_api_key = get_option('side-sup-github-api-token');

                        if (!is_plugin_active('sidebar-support-premium/sidebar-support-premium.php')) { ?>
                            <div class="premium-note">
                                <div
                                    class="premium-text"><?php _e('<a href="http://www.slickremix.com/downloads/sidebar-support-premium-extension/" target="_blank">Premium Extension</a> Required to Edit, but check out the <a href="http://www.sidebar-support.com/" target="_blank">Demo</a> to see how it will look on the front end.', 'sidebar-support') ?></div>
                            </div>
                        <?php } ?>
                        <section>

                            <h3><?php _e('Github', 'sidebar-support') ?></h3>
                            <p>
                                <a href="http://www.slickremix.com/docs/github-setup/"
                                   target="_blank"><?php _e('Click here', 'sidebar-support') ?></a> <?php _e('and see how to get your Personal Access Token.', 'sidebar-support') ?>
                                </p>

                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e("Menu Option", "sidebar-support"); ?></div>
                                <select name="side-sup-menu4" class="side-sup-settings-admin-input" id="side-sup-menu4">
                                    <option value="off"><?php _e('Menu OFF', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu4, 'admins', false) ?>
                                        value="admins"><?php _e('Menu ON for Admins', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu4, 'everyone', false) ?>
                                        value="everyone"><?php _e('Menu ON for Everyone', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div class="side-sup-admin-input-label"><?php _e('Personal Access Token', 'sidebar-support') ?><br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" name="side-sup-github-api-token" id="side-sup-github-api-token"
                                       class="feed-them-social-admin-input" value="<?php echo $side_sup_api_key ?>"
                                       placeholder=""/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->
                        </section>
                        <section>
                            <h3><?php _e('Github User Options', 'sidebar-support') ?></h3>
                            <p><?php _e('List repositories for a specified User', 'sidebar-support') ?></p>

                            <div class="side-sup-admin-input-wrap">
                                <div class="side-sup-admin-input-label"><?php _e('Show By', 'sidebar-support') ?></div>
                                <select name="side-sup-github-user-type" id="side-sup-github-user-type"
                                        class="feed-them-social-admin-input">
                                    <option <?php echo selected($side_sup_type_option, 'hide', false) ?>
                                        value="hide"><?php _e('Hide This Feed', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_type_option, 'all', false) ?>
                                        value="all"><?php _e('All', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_type_option, 'owner', false) ?>
                                        value="owner"><?php _e('Owner', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_type_option, 'member', false) ?>
                                        value="member"><?php _e('Member', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div class="side-sup-admin-input-label"><?php _e('Sort By', 'sidebar-support') ?></div>
                                <select name="side-sup-github-user-sort" id="side-sup-github-user-sort"
                                        class="feed-them-social-admin-input">
                                    <option <?php echo selected($side_sup_sort_option, 'created', false) ?>
                                        value="created"><?php _e('Created', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_sort_option, 'updated', false) ?>
                                        value="updated"><?php _e('Updated', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_sort_option, 'pushed', false) ?>
                                        value="pushed"><?php _e('Pushed', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_sort_option, 'full_name', false) ?>
                                        value="full_name"><?php _e('Full Name', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->
                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Order of Feed', 'sidebar-support') ?></div>
                                <select name="side-sup-github-user-direction" id="side-sup-github-user-direction"
                                        class="feed-them-social-admin-input">
                                    <option <?php echo selected($side_sup_direction_option, 'asc', false) ?>
                                        value="asc"><?php _e('Ascending', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_direction_option, 'desc', false) ?>
                                        value="desc"><?php _e('Descending', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap" style="display: none">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Shortcode Option', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" class="feed-them-social-admin-input"
                                       value="<?php echo '[shortcode here]' ?>" placeholder="" onclick="this.select()"/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->
                        </section>
                        <h3><?php _e('Github Organization Options', 'sidebar-support') ?></h3>
                        <p><?php _e('List repositories for a specified Organization', 'sidebar-support') ?></p>
                        <section>
                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Organization Name', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" name="side-sup-github-org-name" id="side-sup-github-org-name"
                                       class="feed-them-social-admin-input"
                                       value="<?php echo $side_sup_github_org_name ?>"
                                       placeholder="<?php _e('Leave blank to not show Feed.', 'sidebar-support') ?>"/>
                                <div class="clear"></div>
                            </div><!--/feed-them-social-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Type of Feed', 'sidebar-support') ?></div>
                                <select name="side-sup-github-org-type" id="side-sup-github-org-type"
                                        class="feed-them-social-admin-input">
                                    <option <?php echo selected($side_sup_github_org_type, 'public', false) ?>
                                        value="public"><?php _e('Public', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_github_org_type, 'private', false) ?>
                                        value="private"><?php _e('Private', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_github_org_type, 'forks', false) ?>
                                        value="forks"><?php _e('Forks', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_github_org_type, 'sources', false) ?>
                                        value="sources"><?php _e('Sources', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_github_org_type, 'all', false) ?>
                                        value="all"><?php _e('All the Above', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap" style="display: none">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Shortcode Option', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" class="feed-them-social-admin-input"
                                       value="<?php echo '[shortcode here]' ?>" placeholder="" onclick="this.select()"/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                        </section>
                        <input type="submit" class="button button-primary ss-float-left"
                               value="<?php _e('Save All Changes'); ?>"/>

                    </div> <!-- #tab-content4 -->

                    <div id="tab-content5"
                         class="tab-content side-sup-hide-me <?php if (isset($_GET['tab']) && $_GET['tab'] == 'bitbucket') {
                             echo ' pane-active';
                         } ?>">
                        <?php
                        $side_sup_bitbucket_client_id = get_option('side-sup-bitbucket-client-id');
                        $side_sup_bitbucket_client_token = get_option('side-sup-bitbucket-client-token');
                        $side_sup_bitbucket_show_by_for_owner = get_option('side-sup-bitbucket-show-by-for-owner');
                        $side_sup_bitbucket_show_by_for_teamname = get_option('side-sup-bitbucket-show-by-for-teamname');
                        $side_sup_bitbucket_owner = get_option('side-sup-bitbucket-owner');
                        $side_sup_bitbucket_teamname = get_option('side-sup-bitbucket-teamname');

                        if (!is_plugin_active('sidebar-support-premium/sidebar-support-premium.php')) { ?>
                            <div class="premium-note">
                                <div
                                    class="premium-text"><?php _e('<a href="http://www.slickremix.com/downloads/sidebar-support-premium-extension/" target="_blank">Premium Extension</a> Required to Edit, but check out the <a href="http://www.sidebar-support.com/" target="_blank">Demo</a> to see how it will look on the front end.', 'sidebar-support') ?></div>
                            </div>
                        <?php } ?>

                        <h3><?php _e('Bitbucket', 'sidebar-support') ?></h3>
                        <p><?php _e('Please fill in your Client ID and Client Token info first, then you can choose from the other options below.', 'sidebar-support') ?>
                        <br/><a href="http://www.slickremix.com/docs/bitbucket-setup/"
                           target="_blank"><?php _e('Click here', 'sidebar-support') ?></a> <?php _e('and see how to get your Client ID and Token.', 'sidebar-support') ?>
                        </p>
                        <section>
                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e("Menu Option", "sidebar-support"); ?></div>
                                <select name="side-sup-menu5" class="side-sup-settings-admin-input" id="side-sup-menu5">
                                    <option value="off"><?php _e('Menu OFF', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu5, 'admins', false) ?>
                                        value="admins"><?php _e('Menu ON for Admins', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_menu5, 'everyone', false) ?>
                                        value="everyone"><?php _e('Menu ON for Everyone', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div class="side-sup-admin-input-label"><?php _e('Consumer Key', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" name="side-sup-bitbucket-client-id" id="side-sup-bitbucket-client-id"
                                       class="feed-them-social-admin-input"
                                       value="<?php echo $side_sup_bitbucket_client_id ?>" placeholder=""/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div class="side-sup-admin-input-label"><?php _e('Consumer Secret', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" name="side-sup-bitbucket-client-token"
                                       id="side-sup-bitbucket-client-token" class="feed-them-social-admin-input"
                                       value="<?php echo $side_sup_bitbucket_client_token ?>" placeholder=""/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap" style="display: none">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Shortcode Option', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" class="feed-them-social-admin-input"
                                       value="<?php echo '[shortcode here]' ?>" placeholder="" onclick="this.select()"/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->
                        </section>
                        <section>
                            <h3><?php _e('Bitbucket Owner Options', 'sidebar-support') ?></h3>
                            <p><?php _e('Please select from the options below.', 'sidebar-support') ?></p>

                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Owner (user_name)', 'sidebar-support') ?>
                                    <br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" name="side-sup-bitbucket-owner" id="side-sup-bitbucket-owner"
                                       class="feed-them-social-admin-input"
                                       value="<?php echo $side_sup_bitbucket_owner ?>"
                                       placeholder="<?php _e('Leave blank to not show Feed.', 'sidebar-support') ?>"/><br/>

                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Show Repos with Role', 'sidebar-support') ?></div>
                                <select name="side-sup-bitbucket-show-by-for-owner"
                                        id="side-sup-bitbucket-show-by-for-owner" class="feed-them-social-admin-input">
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_owner, 'all', false) ?>
                                        value="all"><?php _e('Show All', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_owner, 'owner', false) ?>
                                        value="owner"><?php _e('Owner', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_owner, 'admin', false) ?>
                                        value="admin"><?php _e('Admin', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_owner, 'contributor', false) ?>
                                        value="contributor"><?php _e('Contributor', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_owner, 'member', false) ?>
                                        value="member"><?php _e('Member', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->
                        </section>
                        <section>
                            <h3><?php _e('Bitbucket Teamname Options', 'sidebar-support') ?></h3>
                            <p><?php _e('Please select from the options below.', 'sidebar-support') ?></p>

                            <div class="side-sup-admin-input-wrap">
                                <div class="side-sup-admin-input-label"><?php _e('Teamname', 'sidebar-support') ?><br/>
                                    <small><?php _e('', 'sidebar-support') ?></small>
                                </div>
                                <input type="text" name="side-sup-bitbucket-teamname" id="side-sup-bitbucket-teamname"
                                       class="feed-them-social-admin-input"
                                       value="<?php echo $side_sup_bitbucket_teamname ?>"
                                       placeholder="<?php _e('Leave blank to not show Feed.', 'sidebar-support') ?>"/>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->

                            <div class="side-sup-admin-input-wrap">
                                <div
                                    class="side-sup-admin-input-label"><?php _e('Show Repos with Role', 'sidebar-support') ?></div>
                                <select name="side-sup-bitbucket-show-by-for-teamname"
                                        id="side-sup-bitbucket-show-by-for-teamname"
                                        class="feed-them-social-admin-input">
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_teamname, 'all', false) ?>
                                        value="all"><?php _e('Show All', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_teamname, 'owner', false) ?>
                                        value="owner"><?php _e('Owner', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_teamname, 'admin', false) ?>
                                        value="admin"><?php _e('Admin', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_teamname, 'contributor', false) ?>
                                        value="contributor"><?php _e('Contributor', 'sidebar-support') ?></option>
                                    <option <?php echo selected($side_sup_bitbucket_show_by_for_teamname, 'member', false) ?>
                                        value="member"><?php _e('Member', 'sidebar-support') ?></option>
                                </select>
                                <div class="clear"></div>
                            </div><!--/side-sup-admin-input-wrap-->
                        </section>
                        <input type="submit" class="button button-primary ss-float-left"
                               value="<?php _e('Save All Changes'); ?>"/>

                    </div> <!-- #tab-content5 -->

                </div>

                <div class="clear"></div>

            </div>
            <div class="clear"></div>
        </form>

        <script>
            jQuery(function ($) {
                $('.action-create').on('click', function () {
                    $('.icp-auto').iconpicker();

                }).trigger('click');
            });
            jQuery(document).ready(function ($) {


                <?php if(isset($_GET['quickresponse']) && $_GET['quickresponse'] == 'open' && $_GET['tab'] == 'quick_responses'){ ?>
                $('#new_post_wrap').css('display', 'block');
                <?php } ?>
                <?php if(isset($_GET['quicklinks']) && $_GET['quicklinks'] == 'open' && $_GET['tab'] == 'quick_links'){ ?>
                $('#new_post_wrap2').css('display', 'block');
                <?php } ?>

                //create hash tag in url for tabs
                jQuery('#tabs').on('click', "label.tabbed", function () {
                    var myURL = document.location;
                    document.location = myURL + "&tab=" + jQuery(this).attr('id');

                })

                // toggles form for quick responses
                $('.ss-create-quick-response').on('click', function (event) {
                    event.preventDefault(); // stop post action
                    $('#new_post_wrap').slideToggle();

                });
                // toggles form for quick links
                $('.ss-create-quick-links').on('click', function (event) {
                    event.preventDefault(); // stop post action
                    $('#new_post_wrap2').slideToggle();

                });

                // Makes sure the media uploader is on the upload tab when openened instead of the media library
                wp.media.controller.Library.prototype.defaults.contentUserSetting = false;


                jQuery(document).on("DOMNodeInserted", function () {
                    // Lock uploads to "Uploaded to this post"
                    jQuery('select.attachment-filters [value="uploaded"]').attr('selected', true).parent().trigger('change');
                });


                // Uploading files
                var file_frame;
                var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                var set_to_post_id = ''<?php //echo $post->ID ?>; // Set this Later


                jQuery('.ls_test_media').live('click', function (event) {

                    event.preventDefault();

                    // If the media frame already exists, reopen it.
                    if (file_frame) {
                        // Set the post ID to what we want
                        file_frame.uploader.uploader.param('post_id', set_to_post_id);
                        // Open frame
                        file_frame.open();
                        return;
                    } else {
                        // Set the wp.media post id so the uploader grabs the ID we want when initialised
                        wp.media.model.settings.post.id = set_to_post_id;
                    }

                    // Create the media frame.
                    file_frame = wp.media(
                        {
                            button: {text: '<?php _e('Insert Media or Zip', 'sidebar-support'); ?>',},
                            frame: 'select',
                            state: 'mystate',
                            // This is commented out so we can see the zip icon too if zips are uploaded
                            // library:   {type: 'image'},
                            multiple: false
                        });

                    file_frame.states.add([

                        new wp.media.controller.Library({
                            id: 'mystate',

                            title: '<?php _e('Insert Media', 'sidebar-support'); ?>',
                            priority: 20,
                            toolbar: 'select',
                            filterable: 'uploaded',
                            library: wp.media.query(file_frame.options.library),
                            multiple: file_frame.options.multiple ? 'reset' : false,
                            editable: false,
                            displayUserSettings: false,
                            displaySettings: false,
                            allowLocalEdits: false,
                        }),
                    ]);

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader
                        attachment = file_frame.state().get('selection').first().toJSON();

                        // Restore the main post ID
                        wp.media.model.settings.post.id = wp_media_post_id;

                        Object:
                            attachment.filename
                        attachment.link
                        attachment.menuOrder

                        // Do something with attachment.id and/or attachment.url here
                        var $edit = $("#fep-post-text");
                        var curValue = $edit.val();
                        var newValue = curValue + ' <a href="' + attachment.url + '">' + attachment.filename + '</a><br/>';
                        $edit.val(newValue);


                    });

                    file_frame.open();

                });
            });
        </script>
        <?php
    }

    /**
     * Side Support Save Item Order
     *
     * @since 1.0.0
     */
    function side_sup_save_item_order() {
        $lists_to_save = array();
        //ADD Quick Responses to save (set in sidebar-builder.js)
        if (isset($_POST['quick_responses_order'])) {
            $list_order = $_POST['quick_responses_order'];
            $lists_to_save = 'quick_responses_order';
        }
        //ADD Quick Links to save (set in sidebar-builder.js)
        if (isset($_POST['quick_links_order'])) {
            $list_order = $_POST['quick_links_order'];
            $lists_to_save = 'quick_links_order';
        }
        //ADD Quick Docs to save (set in sidebar-builder.js)
        if (isset($_POST['quick_docs_order'])) {
            $list_order = $_POST['quick_docs_order'];
            $lists_to_save = 'quick_docs_order';
            print_r($list_order);
        }
        foreach ($list_order as $key => $topic) {
            //update main topic
            update_term_meta($topic['id'], $lists_to_save, $key);
            wp_update_term($topic['id'], 'ss_qr_topics', array('parent' => 0));
            //update children topics and quick items
            if (isset($topic['children'])) {
                $children = $topic['children'];
                //Recursive function to save children
                $this->side_sup_list_save_children($lists_to_save, $children, $topic['id'], $topic['slug']);
            }
        }
    }

    /**
     * Side Support List Save Children
     *
     * @param $lists_to_save
     * @param $children
     * @param int $level
     * @param $slug
     * @since 1.0.0
     */
    function side_sup_list_save_children($lists_to_save, $children, $level = 0, $slug) {
        foreach ($children as $child_key => $child) {
            //Resave Terms
            wp_set_object_terms($child['id'], $slug, 'ss_qr_topics');
            if (isset($child['post'])) {
                //Update Quick Items Menu Order
                wp_update_post(array('ID' => $child['id'], 'menu_order' => $child_key));
            }
            //Update Quick Items Menu Order
            if (isset($child['topic'])) {
                update_term_meta($child['id'], $lists_to_save, $child_key);
                wp_update_term($child['id'], 'ss_qr_topics', array('parent' => $level));
            }
            if (isset($child['children'])) {
                $this->side_sup_list_save_children($lists_to_save, $child['children'], $child['id'], $child['slug']);
            }
        }
    }

    /**
     * Side Support Quick Response Ajax
     *
     * @since 1.0.0
     */
    function side_sup_add_quick_response_ajax() {
        //Check Title is filled out
        $post_title = sanitize_text_field($_POST['post_title']);
        if (empty($post_title)) {
            _e('You must add a title.');
            exit();
        }
        // Here is where we create a new Topic
        if (!empty($_POST['newcat'])) {
            // Check to see if the topic exists already or return our message
            $new_topic_id = term_exists($_POST['newcat'], 'ss_qr_topics');
            if ($new_topic_id == 0) {
                echo $new_topic_id->term_id;
                $topic_name = sanitize_text_field($_POST['newcat']);
                $new_topic_id = wp_insert_term($topic_name, 'ss_qr_topics');
                update_term_meta($new_topic_id, 'side_sup_topic_placement_responses');
            } else {
                _e('That Topic already exists.');
                exit();
            }
        }
        //Set Topics for insert post
        if (!empty($_POST['newcat'])) {
            $topics_terms = sanitize_text_field($_POST['newcat']);
        } elseif (!empty($_POST['ss_qr_topics'])) {
            $topics_terms = sanitize_text_field($_POST['ss_qr_topics']);
        } else {
            _e('You must select an existing Topic or Create a new Topic.');
            exit();
        }
        //Check Quick Response was added
        if (!empty($_POST['editpost'])) {
            $post_content = $_POST['editpost'];
        } else {
            _e('You must add a Quick Response.');
            exit();
        }
        //Insert Post
        $quick_response = array(
            'post_title' => $post_title,
            'post_type' => 'ss_quick_responses',
            'tax_input' => array($topics_terms),
            'post_content' => $post_content,
            'post_status' => 'publish'
        );
        $post_id = wp_insert_post($quick_response);

        //Set Show Title in term meta
        $quick_response_show_title = sanitize_text_field($_POST['quick_response_show_title']);
        add_post_meta($post_id, 'quick_response_show_title', $quick_response_show_title);

        wp_set_object_terms($post_id, $topics_terms, 'ss_qr_topics');

        //Create Topic and/or Item to Append to Sort List via the ajax.
        $display_class = new Display_List;
        //Topic
        $items = is_array($new_topic_id) ? $display_class->display_quick_topic_item('', 'yes', $new_topic_id['term_id']) : '';


        //Quick Item
        $items .= is_array($new_topic_id) ? '<ul class="quick-item-list">' : '';
        $items .= $display_class->display_quick_list_item('quick_responses', 'yes', $post_id, $topics_terms);
        $items .= is_array($new_topic_id) ? '</ul>' : '';

        $items .= is_array($new_topic_id) ? '</li>' : '';

        echo $items;

        exit();
    }

    /**
     * Side Support Add Quick Link Ajax
     *
     * @since 1.0.0
     */
    function side_sup_add_quick_link_ajax() {
        //Check Title is filled out
        $post_title = sanitize_text_field($_POST['post_title2']);
        if (empty($post_title)) {
            _e('You must add a title.');
            exit();
        }
        // Here is where we create a new Topic
        if (!empty($_POST['newcat2'])) {
            // Check to see if the topic exists already or return our message
            $new_topic_id = term_exists($_POST['newcat2'], 'ss_qr_topics');
            if ($new_topic_id == 0) {
                echo $new_topic_id->term_id;
                $topic_name = sanitize_text_field($_POST['newcat2']);
                $new_topic_id = wp_insert_term($topic_name, 'ss_qr_topics');
                //Set Show Title in term meta
                $quick_link_show_title = sanitize_text_field($_POST['quick_link_show_title']);
                update_term_meta($new_topic_id, 'side_sup_topic_placement_links', $quick_link_show_title);
                //If not create new category
            } else {
                _e('That Topic already exists.');
                exit();
            }
        }
        //Set Topics for insert post
        if (!empty($_POST['newcat2'])) {
            $topics_terms = sanitize_text_field($_POST['newcat2']);
        } elseif (!empty($_POST['ss_qr_topics'])) {
            $topics_terms = sanitize_text_field($_POST['ss_qr_topics']);
        } else {
            _e('You must select an existing Topic or Create a new Topic.');
            exit();
        }
        //Check Quick Response was added
        if (!empty($_POST['side_support_quick_link'])) {
            $quick_link = $_POST['side_support_quick_link'];

            //Validate URL
            $quick_links_class = new Quick_Links();
            $url_error = $quick_links_class->validate_input_url($quick_link);
            //If url is not valid throw error and exit
            if (!empty($url_error)) {
                echo $url_error;
                exit();
            }
        } else {
            _e('You must add a Quick Link.');
            exit();
        }
        $quick_link_target = sanitize_text_field($_POST['side_sup_quick_link_target']);

        //Insert Post
        $quick_response = array(
            'post_title' => $post_title,
            'post_type' => 'ss_quick_links',
            'tax_input' => array($topics_terms),
            'post_content' => '',
            'post_status' => 'publish'
        );
        $post_id = wp_insert_post($quick_response);
        //Set Show Title
        add_post_meta($post_id, 'quick_link_show_title', $quick_link_show_title);
        //Set Link Target
        add_post_meta($post_id, 'side_sup_quick_link_target', $quick_link_target);
        //Set Set URL
        add_post_meta($post_id, 'side_sup_quick_link', $quick_link);
        wp_set_object_terms($post_id, $topics_terms, 'ss_qr_topics');

        //Create Topic and/or Item to Append to Sort List via the ajax.
        $display_class = new Display_List;
        //Topic
        $items = is_array($new_topic_id) ? $display_class->display_quick_topic_item('', 'yes', $new_topic_id['term_id']) : '';
        //Quick Item
        $items .= is_array($new_topic_id) ? '<ul class="quick-item-list">' : '';
        $items .= $display_class->display_quick_list_item('quick_links', 'yes', $post_id, $topics_terms);
        $items .= is_array($new_topic_id) ? '</ul>' : '';

        $items .= is_array($new_topic_id) ? '</li>' : '';

        echo $items;

        exit();
    }

    /**
     * Side Support Delete Quick Item AJAX
     *
     * @since 1.0.0
     */
    function side_sup_delete_quick_item_ajax() {
        $permission = check_ajax_referer('side_sup_delete_quick_item_nonce', 'nonce', false);
        if ($permission == false) {
            echo 'error';
        } else {
            wp_delete_post($_REQUEST['id']);
            echo 'success';
        }
        exit();
    }

    /**
     * Side Support Delete Quick Topic AJAX
     *
     * @since 1.0.0
     */
    function side_sup_delete_topic_ajax() {
        $permission = $_REQUEST['id'];
        if ($permission == '') {
            echo 'error';
        } else {
            wp_delete_term($permission, 'ss_qr_topics');
            echo 'success';
        }
        exit();
    }

}

?>