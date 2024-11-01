<?php
namespace Sidebar_Support;
class Core_Functions {
    public $output = "";
    public $feeds_core = "";

    function __construct() {
        $root_file = plugin_dir_path(dirname(__FILE__));
        $this->premium = str_replace('sidebar-support/', 'sidebar-support-premium/', $root_file);

        $this->init();

        $this->feeds_core = new API_Feed_Fetch();
        /*
       * Add Sidebar Support Bar to Admin
       */
        if (is_admin()) {

            add_action('admin_init', array($this, 'side_sup_settings_page'));
            // Admin scripts for only the pages we need them on
            if (isset($_GET['page']) && $_GET['page'] == 'side-sup-settings-page') {
                add_action('admin_enqueue_scripts', array($this, 'side_sup_settings_admin_scripts'));
            }
        }
        /*
        * Add Sidebar Support Bar Scripts to WP Head
        */
        add_action('wp_enqueue_scripts', array($this, 'side_sup_head'));
        /*
        * Add Sidebar Support Bar to WP Footer
        */

        add_action('wp_footer', array($this, 'side_sup_footer'));

        //Settings option. Add Padding to #Prime Wrapper. DO NOT MESS WITH SPACING BELOW.
        $side_sup_custom_css_checked_padding = get_option('side-sup-options-settings-custom-css-options');
        if ($side_sup_custom_css_checked_padding == '1') {
            add_action('wp_head', array($this, 'side_sup_head_padding'));
        }
        //Settings option. Add Custom CSS to the header of Sidebar Support pages only
        $side_sup_custom_css_checked_css = get_option('side-sup-options-settings-custom-css-second');
        if ($side_sup_custom_css_checked_css == '1') {
            add_action('wp_head', array($this, 'side_sup_head_css'));
        }
        //Settings option. Add Padding to #Prime Wrapper. DO NOT MESS WITH SPACING BELOW.
        $side_sup_settings_custom_css = get_option('side-sup-settings-custom-css');
        if ($side_sup_settings_custom_css == '1') {
            add_action('wp_head', array($this, 'side_sup_custom_override_css'));
        }

        // Widget Code, Commenting out until we launch the shortcode options
        // add_filter('widget_text', 'do_shortcode');

        // This is for the side_sup_clear_cache_ajax submission
        add_action('init', array($this, 'side_sup_clear_cache_script'));
        add_action('wp_ajax_clear_cache_ajax', array($this->feeds_core, 'clear_cache_ajax'));
        add_action('wp_ajax_sidebar_sup_save_front_end_settings', array($this, 'sidebar_sup_save_front_end_settings'));

        //Re-order Sub-Menu Items
        add_action('admin_menu', array($this, 'reorder_admin_sub_menus'));
    }
    //**************************************************
    // For Loading in the Admin.
    //**************************************************
    function init() {
        // This is for front end ajax submissions
        add_action('wp_ajax_nopriv_clear_cache_ajax', array($this->feeds_core, 'clear_cache_ajax'));
        // This is for front end ajax submissions
        add_action('wp_ajax_nopriv_sidebar_sup_save_front_end_settings', array($this, 'sidebar_sup_save_front_end_settings'));


        if (is_admin()) {

            add_action('admin_init', array($this, 'side_sup_builder_page_register_settings'));

            // Adds setting page to Sidebar Support menu
            add_action('admin_menu', array($this, 'side_sup_Submenu_Pages'));
            // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
            add_action('admin_enqueue_scripts', array($this, 'side_sup_admin_css'));
        }//end if admin
        //Sidebar Support Admin Bar
        add_action('wp_before_admin_bar_render', array($this, 'side_sup_admin_bar_menu'), 999);
        //Settings option. Add Custom CSS to the header of Sidebar Support pages only
        $side_sup_include_custom_css_checked_css = get_option('side-sup-color-options-settings-custom-css');
        if ($side_sup_include_custom_css_checked_css == '1') {
            add_action('wp_enqueue_scripts', array($this, 'side_sup_color_options_head_css'));
        }
        //Add Custom CSS to the header of Sidebar Pages only
        $side_sup_include_side_sup_custom_css_checked_css = '1'; //get_option( 'side-sup-color-options-settings-custom-css' );
        if ($side_sup_include_side_sup_custom_css_checked_css == '1') {
            add_action('wp_enqueue_scripts', array($this, 'side_sup_side_sup_color_options_head_css'));
        }
    }//end if init

    /**
     * Save front end settings
     *
     * @since 1.0.0
     */
    function sidebar_sup_save_front_end_settings() {
        update_option('side-sup-quick-response-textarea-set', $_POST['side-sup-quick-response-textarea-set']);
        echo $_POST['side-sup-quick-response-textarea-set'];
        exit(); //prevent 0 in the return
    }

    /**
     * Side Support Clear Cache Scripts
     *
     * @since 1.0.0
     */
    function side_sup_clear_cache_script() {
        isset($ssDevModeCache) ? $ssDevModeCache : "";
        isset($ssAdminBarMenu) ? $ssAdminBarMenu : "";
        $ssAdminBarMenu = get_option('side-sup-admin-bar-menu');
        $ssDevModeCache = get_option('side-sup-cache-time');
        if ($ssDevModeCache == '0') {
            if (is_admin()) {
                wp_enqueue_script('side_sup_clear_cache_script', WP_PLUGIN_URL . '/sidebar-support/admin/js/admin.js', array('jquery'));
            }
            else {
                wp_enqueue_script('side_sup_clear_cache_script', WP_PLUGIN_URL . '/sidebar-support/includes/js/front-end.js', array('jquery'));
            }
            wp_enqueue_script('side_sup_clear_cache_script', WP_PLUGIN_URL . '/sidebar-support/admin/js/developer-admin.js', array('jquery'));
            wp_localize_script('side_sup_clear_cache_script', 'ssAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
            wp_enqueue_script('jquery');
            wp_enqueue_script('side_sup_clear_cache_script');
        } else {
            if (is_admin()) {
                wp_enqueue_script('side_sup_clear_cache_script', WP_PLUGIN_URL . '/sidebar-support/admin/js/admin.js', array('jquery'));
            }
            else {
                wp_enqueue_script('side_sup_clear_cache_script', WP_PLUGIN_URL . '/sidebar-support/includes/js/front-end.js', array('jquery'));
            }
            wp_localize_script('side_sup_clear_cache_script', 'ssAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
            wp_enqueue_script('jquery');
            wp_enqueue_script('side_sup_clear_cache_script');
        }
    }

    /**
     * Admin Submenu buttons // add the word setting in place of the default menu page name 'Sidebar Support'
     *
     * @since 1.0.0
     */
    function side_sup_Submenu_Pages() {
        //Sidebar Support Options Page
        $main_settings_page = new Settings_Page();
        //add_menu_page('Sidebar Support', 'Sidebar Support', 'manage_options', 'side-sup-main-menu',  '');
        add_submenu_page(
            'edit.php?post_type=ss_quick_responses',
            __('Settings', 'sidebar-suppport'),
            __('Settings', 'sidebar-suppport'),
            'manage_options',
            'side-sup-settings-page',
            array($main_settings_page, 'Settings_Page')
        );
        //System Info
        $system_info_page = new System_Info();
        add_submenu_page(
            'edit.php?post_type=ss_quick_responses',
            __('System Info', 'sidebar-suppport'),
            __('System Info', 'sidebar-suppport'),
            'manage_options',
            'side-sup-system-info-submenu-page',
            array($system_info_page, 'System_Info_Page')
        );
    }

    /**
     * Admin CSS
     *
     * @since 1.0.0
     */
    function side_sup_admin_css() {
        wp_register_style('side_sup_admin', plugins_url('admin/css/admin.css', dirname(__FILE__)));
        wp_enqueue_style('side_sup_admin');
    }

    /**
     * Reorder Admin Sub Menu
     *
     * @return mixed
     * @since 1.0.0
     */
    function reorder_admin_sub_menus() {
        global $submenu;

        //Unset Menu Items We don't want them to have.
        unset($submenu['edit.php?post_type=ss_quick_responses'][5]);
        unset($submenu['edit.php?post_type=ss_quick_responses'][10]);
        unset($submenu['edit.php?post_type=ss_quick_responses'][15]);

        return $submenu;
    }

    /**
     * Generic Register Settings function
     *
     * @param $settings_name
     * @param $settings
     * @since 1.0.0
     */
    function register_settings($settings_name, $settings) {
        foreach ($settings as $key => $setting) {
            register_setting($settings_name, $setting);
        }
    }

    /**
     * Color Options
     *
     * @since 1.0.0
     */
    function side_sup_color_options_head_css() { ?>
        <style type="text/css"><?php echo get_option('side-sup-color-options-main-wrapper-css-input'); ?></style>
        <?php
    }

    /**
     * Color Options CSS
     *
     * @since 1.0.0
     */
    function side_sup_side_sup_color_options_head_css() {

        wp_register_style('ss-font-awesome', plugins_url('sidebar-support/admin/icon-picker/dist/css/font-awesome.min.css'));
        wp_enqueue_style('ss-font-awesome');

        $side_sup_header_extra_text_color = get_option('side_sup_header_extra_text_color');
        $side_sup_text_color = get_option('side_sup_text_color');
        $side_sup_link_color = get_option('side_sup_link_color');
        $side_sup_link_color_hover = get_option('side_sup_link_color_hover');
        $side_sup_feed_width = get_option('side_sup_feed_width');
        $side_sup_feed_margin = get_option('side_sup_feed_margin');
        $side_sup_feed_padding = get_option('side_sup_feed_padding');
        $side_sup_feed_background_color = get_option('side-sup-feed-background-color');
        $side_sup_grid_posts_background_color = get_option('side-sup-grid-posts-background-color');
        $side_sup_border_bottom_color = get_option('side-sup-border-bottom-color'); ?>

        <style type="text/css">
            <?php if (!empty($side_sup_header_extra_text_color)) { ?>
            .side-sup-jal-single-fb-post .side-sup-jal-fb-user-name {
                color: <?php echo $side_sup_header_extra_text_color ?> !important;
            }

            <?php }
        if (!empty($side_sup_text_color)) { ?>
            .side-sup-simple-fb-wrapper .side-sup-jal-single-fb-post,
            .side-sup-simple-fb-wrapper .side-sup-jal-fb-description-wrap,
            .side-sup-simple-fb-wrapper .side-sup-jal-fb-post-time,
            .side-sup-slicker-quick-response-posts .side-sup-jal-single-fb-post,
            .side-sup-slicker-quick-response-posts .side-sup-jal-fb-description-wrap,
            .side-sup-slicker-quick-response-posts .side-sup-jal-fb-post-time {
                color: <?php echo $side_sup_text_color ?> !important;
            }

            <?php }
        if (!empty($side_sup_link_color)) { ?>
            .side-sup-simple-fb-wrapper .side-sup-jal-single-fb-post a,
            .side-sup-fb-load-more-wrapper .side-sup-fb-load-more,
            .side-sup-slicker-quick-response-posts .side-sup-jal-single-fb-post a,
            .side-sup-fb-load-more-wrapper .side-sup-fb-load-more {
                color: <?php echo $side_sup_link_color ?> !important;
            }

            <?php }
        if (!empty($side_sup_link_color_hover)) { ?>
            .side-sup-simple-fb-wrapper .side-sup-jal-single-fb-post a:hover,
            .side-sup-simple-fb-wrapper .side-sup-fb-load-more:hover,
            .side-sup-slicker-quick-response-posts .side-sup-jal-single-fb-post a:hover,
            .side-sup-slicker-quick-response-posts .side-sup-fb-load-more:hover {
                color: <?php echo $side_sup_link_color_hover ?> !important;
            }

            <?php }
        if (!empty($side_sup_feed_width)) { ?>
            .side-sup-simple-fb-wrapper, .side-sup-fb-header-wrapper, .side-sup-fb-load-more-wrapper {
                max-width: <?php echo $side_sup_feed_width ?> !important;
            }

            <?php }
        if (!empty($side_sup_feed_margin)) { ?>
            .side-sup-simple-fb-wrapper, .side-sup-fb-header-wrapper, .side-sup-fb-load-more-wrapper {
                margin: <?php echo $side_sup_feed_margin ?> !important;
            }

            <?php }
        if (!empty($side_sup_feed_padding)) { ?>
            .side-sup-simple-fb-wrapper {
                padding: <?php echo $side_sup_feed_padding ?> !important;
            }

            <?php }
        if (!empty($side_sup_feed_background_color)) { ?>
            .side-sup-simple-fb-wrapper, .side-sup-fb-load-more-wrapper .side-sup-fb-load-more {
                background: <?php echo $side_sup_feed_background_color ?> !important;
            }

            <?php }
        if (!empty($side_sup_grid_posts_background_color)) { ?>
            .side-sup-slicker-quick-response-posts .side-sup-jal-single-fb-post {
                background: <?php echo $side_sup_grid_posts_background_color ?> !important;
            }

            <?php }
        if (!empty($side_sup_border_bottom_color)) { ?>
            .side-sup-slicker-quick-response-posts .side-sup-jal-single-fb-post, .side-sup-jal-single-fb-post {
                border-bottom: 1px solid <?php echo $side_sup_border_bottom_color ?> !important;
            }

            <?php } ?>
        </style>
        <?php
    }

    /**
     * Create our custom menu in the admin bar.
     *
     * @since 1.0.0
     */
    function side_sup_admin_bar_menu() {
        global $wp_admin_bar;
        isset($side_sup_DevModeCache) ? $side_sup_DevModeCache : "";
        isset($side_sup_AdminBarMenu) ? $side_sup_AdminBarMenu : "";
        $side_sup_AdminBarMenu = get_option('side-sup-admin-bar-menu');
        $side_sup_DevModeCache = get_option('side-sup-cache-time');
        if (!is_super_admin() || !is_admin_bar_showing() || $side_sup_AdminBarMenu == 'hide-admin-bar-menu')
            return;


        $wp_admin_bar->add_menu(array(
            'id' => 'side_sup_admin_bar',
            'title' => __('Sidebar Support', 'side-sup'),
            'href' => false));
        //Cache Menu
        if ($side_sup_DevModeCache == '0') {
            $wp_admin_bar->add_menu(array(
                    'id' => 'side_sup_admin_bar_clear_cache',
                    'parent' => 'side_sup_admin_bar',
                    'title' => __('Cache clears on page refresh now', 'side-sup'),
                    'href' => false)
            );
        } else {
            $wp_admin_bar->add_menu(
                array(
                    'id' => 'side_sup_admin_bar_clear_cache',
                    'parent' => 'side_sup_admin_bar',
                    'title' => __('Clear Cache', 'side-sup'),
                    'href' => '#')
            );
        }
        //Add Quick Repsonses
        $wp_admin_bar->add_menu(array(
                'id' => 'side_sup_admin_bar_add_response',
                'parent' => 'side_sup_admin_bar',
                'title' => __('Create New Quick Response', 'side-sup'),
                'href' => admin_url('edit.php?post_type=ss_quick_responses&page=side-sup-sidebar-builder-page&quickresponse=open&tab=quick_responses')
            )
        );
        //Add Quick Link
        $wp_admin_bar->add_menu(array(
                'id' => 'side_sup_admin_bar_add_link',
                'parent' => 'side_sup_admin_bar',
                'title' => __('Create New Quick Link', 'side-sup'),
                'href' => admin_url('edit.php?post_type=ss_quick_responses&page=side-sup-sidebar-builder-page&quicklinks=open&tab=quick_links')
            )
        );
        //Sidebar Builder
        $wp_admin_bar->add_menu(array(
                'id' => 'side_sup_admin_bar_sidebar_builder',
                'parent' => 'side_sup_admin_bar',
                'title' => __('Sidebar Builder', 'side-sup'),
                'href' => admin_url('edit.php?post_type=ss_quick_responses&page=side-sup-sidebar-builder-page'))
        );
        //Sidebar Settings
        $wp_admin_bar->add_menu(array(
                'id' => 'side_sup_admin_bar_settings',
                'parent' => 'side_sup_admin_bar',
                'title' => __('Settings', 'side-sup'),
                'href' => admin_url('edit.php?post_type=ss_quick_responses&page=side-sup-settings-page'))
        );
    }



    /**
     * Sidebar Builder Page settings.
     *
     * @since 1.0.0
     */
    function side_sup_builder_page_register_settings() {
        $settings = array(
            'side-sup-menu1',
            'side-sup-menu2',
            'side-sup-menu3',
            'side-sup-menu4',
            'side-sup-menu5',
            'side-sup-menu6',
            'side-sup-show-responses-list-closed',
            'side-sup-show-links-list-closed',
            'side-sup-quick-resp-icon',
            'side-sup-quick-link-icon',
            'side-sup-plugin-themes-option',
            'side-sup-wordpress-username',
            //Github
            'side-sup-github-user-type',
            'side-sup-github-user-sort',
            'side-sup-github-user-direction',
            'side-sup-github-org-name',
            'side-sup-github-org-type',
            'side-sup-github-api-token',
            //Bitbucket
            'side-sup-bitbucket-client-id',
            'side-sup-bitbucket-client-token',
            'side-sup-bitbucket-owner',
            'side-sup-bitbucket-show-by-for-owner',
            'side-sup-bitbucket-teamname',
            'side-sup-bitbucket-show-by-for-teamname',
        );
        $this->register_settings('side-sup-builder-settings', $settings);
    }


    /**
     * Side Support Front End Settings
     *
     * @since 1.0.0
     */
    function side_sup_front_end_settings() {

        $settings = array(
            //Front End Options
            'side-sup-quick-response-textarea-set',
        );
        $this->register_settings('side-sup-front-end-settings', $settings);
    }


    /**
     * Side Support Settings Page
     *
     * @since 1.0.0
     */
    function side_sup_settings_page() {

        $settings = array(
            //Cache
            'side-sup-clear-cache-developer-mode',
            'side-sup-cache-time',
            'side-sup-admin-bar-menu',

            //Show on pages or posts
            'side-sup-page-options',
            'side-sup-post-options',
            'side-sup-category-options',
            'side-sup-archive-options',
            'side-sup-home-options',
            'side-sup-search-options',
            'side-sup-errorpage-options',
            'side-sup-tags-options',

            //Color Options
            'side-sup-options-settings-custom-css',
            'side-sup-settings-custom-css',

            'side-sup-main-wrapper-css-input',
            'side-sup-settings-admin-textarea-css',

            'side-sup-main-wrapper-width-input',
            'side-sup-options-settings-custom-css-options',
            'side-sup-bottom-position',
            'side-sup-options-mobile',
            'side-sup-options-tablets',
            'side-sup-options-settings-custom-css-second',

            'side-sup-quick-response-icon-color',
            'side-sup-quick-response-background-color',
            'side-sup-quick-links-icon-color',
            'side-sup-quick-links-background-color',
            'side-sup-github-icon-color',
            'side-sup-github-background-color',
            'side-sup-panels-background',
            'side-sup-text-color',
            'side-sup-close-button',
            'side-sup-quick-links-info-background',
            'side-sup-link-color',
            'side-sup-link-hover-color',
            'side-sup-gitlab-icon-color',
            'side-sup-gitlab-background-color',
            'side-sup-wordpress-icon-color',
            'side-sup-wordpress-background-color',
            'side-sup-bitbucket-icon-color',
            'side-sup-bitbucket-background-color',
            'side-sup-border-bottom-color',
            'side-sup-h1-color',
            'side-sup-h2-color',
            'side-sup-h3-color',
            'side-sup-button-background-color',
        );


        //Add Custom Post Types to settings
        $args = array(
            'public' => true,
            '_builtin' => false
        );

        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'

        $post_types = get_post_types($args, $output, $operator);

        foreach ($post_types as $post_type) {
            //Lowercase for setting name
            $lower_post_type = strtolower($post_type);
            $final_post_type_name = 'side-sup-settings-pt-' . $lower_post_type;

            $settings[] = $final_post_type_name;
        }

        $this->register_settings('side-sup-settings', $settings);
    }

    /**
     * Side Support Settings Admin Scripts
     *
     * @since 1.0.0
     */
    function side_sup_settings_admin_scripts() {
        wp_enqueue_script('jquery');
    }

    /**
     * Side Support Footer
     * Here start the Sidebar Support Bar that goes in the footer
     *
     * @since 1.0.0
     */
    function side_sup_footer() {
        $page = is_page();
        $side_sup_post = is_singular('post');
        $category = is_category();
        $archive = is_archive();
        $home = is_home();
        $search = is_search();
        $errorpage = is_404();
        $tags = is_tag();
        //special call to only show bar on side-sup bar page on sidebar-support.com
        // $Sidebar Supportcomhost = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $print_here = 0;

        if ($side_sup_post && get_option('side-sup-post-options') == '1' || $page && get_option('side-sup-page-options') == '1' || $category && get_option('side-sup-category-options') == '1' || $archive && get_option('side-sup-archive-options') == '1' || $home && get_option('side-sup-home-options') == '1' || $search && get_option('side-sup-search-options') == '1' || $errorpage && get_option('side-sup-errorpage-options') == '1' || $tags && get_option('side-sup-tags-options') == '1') {
            $print_here = 1;
        }

        $args = array(
            'public' => true,
            '_builtin' => false
        );

        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'

        $post_types = get_post_types($args, $output, $operator);

        foreach ($post_types as $post_type) {
            //Lowercase for class
            $lower_post_type = strtolower($post_type);
            $final_post_type_name = 'side-sup-settings-pt-' . $lower_post_type;

            if (is_singular($post_type) && get_option($final_post_type_name) == '1') {
                $print_here = 1;
            }
        }

        if ($print_here == 1) {

            $side_sup_quick_resp_icon = get_option('side-sup-quick-resp-icon') ? get_option('side-sup-quick-resp-icon') : 'fa-support';
            $side_sup_quick_link_icon = get_option('side-sup-quick-link-icon') ? get_option('side-sup-quick-link-icon') : 'fa-link';
            $side_sup_menu1 = get_option('side-sup-menu1') ? get_option('side-sup-menu1') : 'off';
            $side_sup_menu2 = get_option('side-sup-menu2') ? get_option('side-sup-menu2') : 'off';
            $side_sup_menu3 = get_option('side-sup-menu3') ? get_option('side-sup-menu3') : 'off';
            $side_sup_menu4 = get_option('side-sup-menu4') ? get_option('side-sup-menu4') : 'off';
            $side_sup_menu5 = get_option('side-sup-menu5') ? get_option('side-sup-menu5') : 'off';
            // $side_sup_menu6 = get_option( 'side-sup-menu6' ) ? get_option( 'side-sup-menu6' ) : 'off';
            $side_sup_quick_links_textarea = get_option('side-sup-quick-response-textarea-set') ? get_option('side-sup-quick-response-textarea-set') : 'textarea';


            ?>

            <ul class="side-sup-social-bar-icons-wrap" id="side-sup-social-bar-icons-wrap">
                <?php if ($side_sup_menu1 == 'everyone' || current_user_can('manage_options') && $side_sup_menu1 == 'admins') {

                    $feed_type = get_option('side-sup-quick-response-feed-type');
                    ?>
                    <li class="toggle  menu1">
                    <div id="open-quick-response" class="fa-fw <?php echo $side_sup_quick_resp_icon ?>"></div>
                    <ul class="side-sup-sidebar-menu">
                        <li>
                            <div id="quick-response-sidebar" class="side-sup-social-panels">
                                <div class="overflow-wrapper" onmouseover="document.body.style.overflow='hidden';"
                                     onmouseout="document.body.style.overflow='auto';">
                                    <?php if (current_user_can('manage_options')) { ?>
                                        <div class="quick-response-settings-menu">
                                            <div class="quick-response-settings-title">
                                                <div
                                                    class="side-sup-sidebar-settings-icon"><?php _e('Settings', 'sidebar-support'); ?></div>
                                                <a href="<?php echo admin_url('edit.php?post_type=ss_quick_responses&page=side-sup-sidebar-builder-page&quickresponse=open&tab=quick_responses'); ?>"
                                                   class="quick-response-settings-title-second"><?php _e('Create New Quick Response', 'sidebar-support'); ?></a>
                                            </div>

                                            <div
                                                class="quick-response-settings-content" <?php if (get_option('side-sup-quick-response-textarea-set') == '') {
                                                echo 'style="display:block"';
                                            } ?> >
                                                <div id="side-sup-settings-admin-form">
                                                    <?php // get our registered settings from the core functions
                                                    settings_fields('side-sup-front-end-settings'); ?>
                                                    <div
                                                        class="quick-response-settings-text"><?php _e('<strong>NOTE:</strong> You must set this field below to start, then these settings will be closed on the next page load. <br/><br/>
<strong>This menu is meant to do 2 things.</strong><br/>
<strong>1.</strong> Copy and paste text for emails or other: To copy a response, click the icon next to the response when hovering over it. Now use Ctrl + V (PC) or Cmd + V (Mac) and paste the response where you need it to be. (Does not work in safari) <br/><br/>
<strong>2.</strong> Click on a Response below and it auto paste to the designated text box of your choice: On the page click in the text box where you want your responses to appear then click the Save Settings button below. Now your responses will always be copied to the text box you assigned when you click on them from the sidebar.', 'sidebar-support'); ?></div>
                                                    <div class="quick-response-settings-input">
                                                        <form method="post" id="form-settings" action="">

                                                            <?php wp_nonce_field('side-sup-front-end-settings', 'side-sup-front-end-settings_nonce', false); ?>

                                                            <input name="side-sup-quick-response-textarea-set" id="side-sup-quick-response-textarea-set" value="<?php echo $side_sup_quick_links_textarea; ?>"/>

                                                            <div class="sidebar-sup-submit-wrap">
                                                                <div id="sidebar-support-submit-front-end">
                                                                    <?php _e('Save Settings', 'sidebar-support'); ?>
                                                                </div>
                                                                <div class="front-end-close-button-ss">
                                                                    <?php _e('Close', 'sidebar-support'); ?>
                                                                </div>
                                                                <div class="fa fa-cog fa-3x fa-fw sidebar-sup-loader"></div>
                                                                <div class="fa fa-check-circle fa-3x fa-fw sidebar-sup-success"></div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    echo do_shortcode('[side_sup_display_list list=quick_responses sortable=no]'); ?>
                                    <div id="side-sup-quick-response-textarea-id" style="display: none"><?php echo $side_sup_quick_links_textarea; ?></div>

                                </div>
                                <div class="close-side-sup-panel side-sup-close-fb">X</div>
                            </div>
                        </li>
                    </ul>
                    </li><?php }
                if ($side_sup_menu2 == 'everyone' || current_user_can('manage_options') && $side_sup_menu2 == 'admins') {
                    ?>
                    <li class="toggle menu2">
                    <div id="open-quick-links" class="fa-fw <?php echo $side_sup_quick_link_icon ?>"></div>
                    <ul class="side-sup-sidebar-menu">
                        <li>
                            <div id="quick-links-sidebar" class="side-sup-social-panels">
                                <div class="overflow-wrapper" onmouseover="document.body.style.overflow='hidden';"
                                     onmouseout="document.body.style.overflow='auto';">

                                    <?php if (current_user_can('manage_options')) { ?>
                                        <div class="quick-response-settings-menu">
                                            <div class="quick-response-settings-title">
                                                <a href="<?php echo admin_url('edit.php?post_type=ss_quick_responses&page=side-sup-sidebar-builder-page&quicklinks=open&tab=quick_links'); ?>"
                                                   class="quick-response-settings-title-second"><?php _e('Create New Quick Link', 'sidebar-support'); ?></a>
                                            </div>
                                        </div>
                                    <?php }
                                    echo do_shortcode('[side_sup_display_list list=quick_links sortable=no]'); ?>
                                </div>

                                <div class="close-side-sup-panel side-sup-close-quick-links">X</div>
                            </div>
                        </li>
                    </ul>
                    </li><?php }
                if ($side_sup_menu3 == 'everyone' || current_user_can('manage_options') && $side_sup_menu3 == 'admins') { ?>
                    <li class="toggle menu3">
                    <div id="open-wordpress-org"></div>
                    <ul class="side-sup-sidebar-menu">
                        <li>
                            <div id="wordpress-org-sidebar" class="side-sup-social-panels">
                                <div class="overflow-wrapper" onmouseover="document.body.style.overflow='hidden';"
                                     onmouseout="document.body.style.overflow='auto';">
                                    <div
                                        class="side-sup-section-header"><?php _e('Wordpress', 'sidebar-support'); ?></div>
                                    <?php $WP_Feed = new WordPress_Feed;
                                    $side_sup_wordpress_username = get_option('side-sup-wordpress-username');
                                    echo $WP_Feed->get_plugins_by_author($side_sup_wordpress_username); ?></div>
                                <div class="close-side-sup-panel side-sup-close-github">X</div>
                            </div>
                        </li>
                    </ul>
                    </li><?php }
                if (is_plugin_active('sidebar-support-premium/sidebar-support-premium.php')) {
                    if ($side_sup_menu4 == 'everyone' || current_user_can('manage_options') && $side_sup_menu4 == 'admins') { ?>
                        <li class="toggle menu4">
                        <div id="open-github"></div>
                        <ul class="side-sup-sidebar-menu">
                            <li>
                                <div id="github-sidebar" class="side-sup-social-panels">
                                    <div class="overflow-wrapper" onmouseover="document.body.style.overflow='hidden';"
                                         onmouseout="document.body.style.overflow='auto';">
                                        <div
                                            class="side-sup-section-header"><?php _e('Github', 'sidebar-support'); ?></div>
                                        <?php $github_feed_class = new GitHub_Feed();
                                        echo $github_feed_class->get_github_feeds(array('github_user', 'github_org')); ?>
                                    </div>
                                    <div class="close-side-sup-panel side-sup-close-github">X</div>
                                </div>
                            </li>
                        </ul>
                        </li><?php }
                    if ($side_sup_menu5 == 'everyone' || current_user_can('manage_options') && $side_sup_menu5 == 'admins') { //display_github_feeds(array('github_user_repos','github_org_repos')?>
                        <li class="toggle menu5">
                        <div id="open-bitbucket"></div>
                        <ul class="side-sup-sidebar-menu">
                            <li>
                                <div id="bitbucket-sidebar" class="side-sup-social-panels">
                                    <div class="overflow-wrapper" onmouseover="document.body.style.overflow='hidden';"
                                         onmouseout="document.body.style.overflow='auto';">
                                        <div
                                            class="side-sup-section-header"><?php _e('Bitbucket', 'sidebar-support'); ?></div>
                                        <?php $bitbucket_feed_class = new BitBucket_Feed();
                                        echo $bitbucket_feed_class->get_bitbucket_feeds(array('bitbuck_owner', 'bitbuck_teamname')); ?>
                                    </div>
                                    <div class="close-side-sup-panel side-sup-close-github">X</div>
                                </div>
                            </li>
                        </ul>
                        </li><?php }
                }
                // Ready for a new Tab
                //  if ($side_sup_menu6 == 'everyone' || current_user_can('manage_options') && $side_sup_menu6 == 'admins') {
                //	<li class="toggle menu6">
                //		<div id="open-gitlab"></div>
                //		<ul class="side-sup-sidebar-menu">
                //			<li>
                //				<div id="gitlab-sidebar" class="side-sup-social-panels">
                //					<div class="overflow-wrapper" onmouseover="document.body.style.overflow='hidden';" onmouseout="document.body.style.overflow='auto';" ></div>
                //					<div class="close-side-sup-panel side-sup-close-github">X</div>
                //				</div>
                //			</li>
                //		</ul>
                //	</li>
                ?>
            </ul>
        <?php } // end if is page, post etc
    }

    /**
     * Sidebar Support Head Scripts and Styles
     *
     * @since 1.0.0
     */
    function side_sup_head() {
        wp_register_style('side-sup-styles', plugins_url('css/styles.css', dirname(__FILE__)));
        wp_enqueue_style('side-sup-styles');
        wp_enqueue_script('jquery');
    }

    /**
     * Sidebar Support front end padding
     *
     * @since 1.0.0
     */
    function side_sup_head_padding() {
        ?>
        <style type="text/css">
        <?php //social feed panel width
            $side_sup_main_wrapper_css_input =  get_option('side-sup-main-wrapper-width-input');
            if ($side_sup_main_wrapper_css_input == ' ' || $side_sup_main_wrapper_css_input == '') {

            }
        else { ?>
        .side-sup-social-panels {
            max-width: <?php echo get_option('side-sup-main-wrapper-width-input');?> !important;
        }
        <?php }
         //position
            $side_sup_bottom_position =  get_option('side-sup-bottom-position');
            if ($side_sup_bottom_position == 1) { ?>
        #side-sup-social-bar-icons-wrap {
            top: auto;
            bottom: 0px !important;
        }
        #side-sup-social-bar-icons-wrap li {
        }
        <?php }
         //show side-sup bar on mobile
            $side_sup_options_mobile =  get_option('side-sup-options-mobile');
            if ($side_sup_options_mobile == 1) { ?>
        @media (max-width: 324px) {
            #side-sup-social-bar-icons-wrap {
                display: none !important;
            }
        }
        <?php }
         //show side-sup bar on tablet
            $side_sup_options_tablets =  get_option('side-sup-options-tablets');
            if ($side_sup_options_tablets == 1) { ?>
        @media (max-width: 768px) {
            #side-sup-social-bar-icons-wrap {
                display: none !important;
            }
        }
        <?php } ?>
        </style><?php
    }

    /**
     * Sidebar Support Head CSS
     *
     * @since 1.0.0
     */
    function side_sup_head_css() {
        ?>
        <style type="text/css"><?php echo get_option('side-sup-settings-admin-textarea-css'); ?></style><?php
    }

    /**
     * Sidebar Support Custom override CSS
     *
     * @since 1.0.0
     */
    function side_sup_custom_override_css() {
        ?>
        <style>
        #side-sup-social-bar-icons-wrap #open-quick-response {
            color: <?php echo get_option('side-sup-quick-response-icon-color'); ?> !important;
            background: <?php echo get_option('side-sup-quick-response-background-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap #open-quick-links {
            color: <?php echo get_option('side-sup-quick-links-icon-color'); ?> !important;
            background: <?php echo get_option('side-sup-quick-links-background-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap #open-github {
            color: <?php echo get_option('side-sup-github-icon-color'); ?> !important;
            background: <?php echo get_option('side-sup-github-background-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap #open-wordpress-org {
            color: <?php echo get_option('side-sup-wordpress-icon-color'); ?> !important;
            background: <?php echo get_option('side-sup-wordpress-background-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap #open-gitlab {
            color: <?php echo get_option('side-sup-gitlab-icon-color'); ?> !important;
            background: <?php echo get_option('side-sup-gitlab-background-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap #open-bitbucket {
            color: <?php echo get_option('side-sup-bitbucket-icon-color'); ?> !important;
            background: <?php echo get_option('side-sup-bitbucket-background-color'); ?> !important;
        }

        .side-sup-menuEdit, #side-sup-social-bar-icons-wrap ul.side_sup_feed_list li {
            border-bottom: 1px solid <?php echo get_option('side-sup-border-bottom-color'); ?> !important
        }

        #side-sup-social-bar-icons-wrap #quick-response-sidebar, #side-sup-social-bar-icons-wrap #quick-links-sidebar, #quick-links-sidebar .side-sup-quick-links-div, #side-sup-social-bar-icons-wrap #github-sidebar {
            background: <?php echo get_option('side-sup-panels-background'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap #wordpress-org-sidebar, #side-sup-social-bar-icons-wrap #github-sidebar, #side-sup-social-bar-icons-wrap #bitbucket-sidebar, #side-sup-social-bar-icons-wrap #gitlab-sidebar {
            background: <?php echo get_option('side-sup-panels-background'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap .quick-item-list, #side-sup-social-bar-icons-wrap .quick-item-list p {
            color: <?php echo get_option('side-sup-text-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap .side-sup-sidebar-menu .itemTitle, .side-sup-section-header {
            color: <?php echo get_option('side-sup-h1-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap .side-sup-sidebar-menu ul.quick-item-list .itemTitle {
            color: <?php echo get_option('side-sup-h2-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap .side-sup-section-title {
            color: <?php echo get_option('side-sup-h3-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap .close-side-sup-panel {
            color: <?php echo get_option('side-sup-close-button'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap a, #side-sup-social-bar-icons-wrap .side-sup-sidebar-menu .side-sup-quick-link-title span.itemTitle {
            color: <?php echo get_option('side-sup-link-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap a:hover {
            color: <?php echo get_option('side-sup-link-hover-color'); ?> !important;
        }

        #side-sup-social-bar-icons-wrap a.side_sup_feed_btn {
            background: <?php echo get_option('side-sup-button-background-color'); ?> !important;
        }
        </style><?php
    }
}//END Class
?>