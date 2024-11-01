<?php
/*
Plugin Name: Sidebar Support
Plugin URI: http://slickremix.com/
Description: This plugin allows you to create Quick Responses to help formulate responses easily, Quick links for easy navigation, and Wordpress, Github or Bitbucket lists to manage repositories.
Version: 1.0.0
Author: SlickRemix
Author URI: http://slickremix.com/
Requires at least: wordpress 4.0.0
Tested up to: WordPress 4.5.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 * @package    		Sidebar Support
 * @category   		Core
 * @author     		SlickRemix
 * @copyright  		Copyright (c) 2012-2016 SlickRemix

If you need support or want to tell us thanks please contact us at support@slickremix.com or use our support forum on slickremix.com.
*/

define('SIDE_SUP_PLUGIN_PATH', plugins_url());


final class Sidebar_Support {
    //Main Instance of Display Posts Feed
    private static $instance;

    /**
     * Create Sidebar Shortcode Instance
     *
     * @return Sidebar_Support
     * @since 1.0.0
     */
    public static function instance() {
        if (!isset(self::$instance) && !(self::$instance instanceof Sidebar_Support)) {
            self::$instance = new Sidebar_Support;
            self::$instance->setup_constants();
            //add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

            //Include the files
            self::$instance->includes();
            //Error Handler
            self::$instance->error_handler = new Sidebar_Support\Error_Handler();

            //Admin
            self::$instance->system_info = new Sidebar_Support\System_Info();
            self::$instance->settings_page = new Sidebar_Support\Settings_Page();
            self::$instance->sidebar_builder = new Sidebar_Support\Sidebar_Builder();
            //Topics (Taxonomy)
            self::$instance->topics = new Sidebar_Support\Topics();
            //Quick Responses (Custom Post Type)
            self::$instance->quick_reponses = new Sidebar_Support\Quick_Responses();
            //Quick Links (Custom Post Type)
            self::$instance->quick_links = new Sidebar_Support\Quick_Links();
            //Display List
            self::$instance->display_list = new Sidebar_Support\Display_List();
            //Core
            self::$instance->functions = new Sidebar_Support\Core_Functions();
        }

        return self::$instance;
    }

    /**
     * Setup plugin constants
     *
     * @since 1.0.0
     */
    private function setup_constants() {
        // Plugin version
        if (!defined('SIDE_SUP_VERSION')) {
            define('SIDE_SUP_VERSION', '1.0.0');
        }
        // Plugin Folder Path
        if (!defined('SIDE_SUP_PLUGIN_PATH')) {
            define('SIDE_SUP_PLUGIN_PATH', plugins_url());
        }
        // Plugin Directoy Path
        if (!defined('SIDE_SUP_PLUGIN_FOLDER_DIR')) {
            define('SIDE_SUP_PLUGIN_FOLDER_DIR', plugin_dir_path(__FILE__));
        }
    }

    /**
     * Incude Everything we need
     *
     * @since 1.0.0
     */
    private function includes() {

        //Error Handler
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/error-handler.php');
        //Admin Pages
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'admin/system-info.php');
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'admin/settings-page.php');
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'admin/sidebar-builder-class.php');

        //Topics (Taxonomy)
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/topics/topics-class.php');
        //Quick Responses (Custom Post Type)
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/quick-responses/quick-response-class.php');
        //Quick Links (Custom Post Type)
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/quick-links/quick-links-class.php');

        //Display List
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/display-list/display-list-class.php');

        //Feeds Core
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/feeds/oauth2.php');
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/feeds/feeds-core-class.php');
        //WordPress Feed
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/feeds/wordpress-feed-class.php');

        //Core Functions Class
        include(SIDE_SUP_PLUGIN_FOLDER_DIR . 'includes/core-functions-class.php');

        //Display Shortcode Class
    }
}//END FINAL DAS CLASS

function ap_action_init() {
    // Localization
    load_plugin_textdomain('sidebar_support', false, basename(dirname(__FILE__)) . '/languages');
}

// Add actions
add_action('init', 'ap_action_init');
if (!function_exists('is_plugin_active'))
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
// Makes sure the plugin is defined before trying to use it
// Make sure php version is greater than 5.3
if (function_exists('phpversion'))
    $phpversion = phpversion();
$phpcheck = '5.2.9';
if ($phpversion > $phpcheck) {
    // Include our own Settings link to plugin activation and update page. NOT NEEDED IN THIS PLUGIN
    add_filter("plugin_action_links_" . plugin_basename(__FILE__), "side_sup_plugin_actions", 10, 4);

    /**
     * Sidebar Support Plugin Actions
     *
     * @param $actions
     * @param $plugin_file
     * @param $plugin_data
     * @param $context
     * @return mixed
     * @since 1.0.0
     */
    function side_sup_plugin_actions($actions, $plugin_file, $plugin_data, $context) {
        array_unshift($actions, "<a href=\"" . menu_page_url('side-sup-settings-page', false) . "\">" . __("Settings") . "</a>");

        return $actions;
    }
} // end if php version check
else {
    // if the php version is not at least 5.3 do action
    //deactivate_plugins( 'das-premium/das-premium.php' );
    deactivate_plugins('sidebar-support/sidebar-support.php');
    add_action('admin_notices', 'SIDE_SUP_php_check');
    /**
     *
     *
     * @since 1.0.0
     */
    function side_sup_php_check() {
        echo '<div class="error"><p>' . __('<strong>Warning:</strong> Your php version is ' . phpversion() . '. You need to be running at least 5.3 or greater to use this plugin. Please upgrade the php by contacting your host provider. Some host providers will allow you to change this yourself in the hosting control panel too.<br/><br/>If you are hosting with BlueHost or Godaddy and the php version above is saying you are running 5.2.17 but you are really running something higher please <a href="https://wordpress.org/support/topic/php-version-difference-after-changing-it-at-bluehost-php-config?replies=4" target="_blank">click here for the fix</a>. If you cannot get it to work using the method described in the link please contact your host provider and explain the problem so they can fix it.', 'side_sup') . '</p></div>';
    }
}

/**
 * Returns current plugin version
 *
 * @return mixed
 * @since 1.0.0
 */
function side_sup_version() {
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];

    return $plugin_version;
}

add_filter('plugin_row_meta', 'side_sup_add_leave_feedback_link', 10, 2);

/**
 * Include Leave feedback, Get support and Plugin info links to plugin activation and update page.
 *
 * @param $links
 * @param $file
 * @return mixed
 * @since 1.0.0
 */
function side_sup_add_leave_feedback_link($links, $file) {
    if ($file === plugin_basename(__FILE__)) {
        //$links['feedback'] = '<a href="http://wordpress.org/support/view/plugin-reviews/design-approval-system" target="_blank">' . __( 'Leave feedback', 'gd_quicksetup' ) . '</a>';
        //$links['support']  = '<a href="http://wordpress.org/support/plugin/design-approval-system" target="_blank">' . __( 'Get support', 'gd_quicksetup' ) . '</a>';
    }

    return $links;
}

/**
 * Sidebar Support Start it up!
 *
 * @return Sidebar_Support
 * @since 1.0.0
 */
function sidebar_support() {
    return Sidebar_Support::instance();
}

// Get Sidebar Support Running
sidebar_support(); 