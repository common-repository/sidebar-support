<?php
namespace Sidebar_Support;
class System_Info {
	function __construct()
	{
		if (is_admin()) {
			add_action('admin_enqueue_scripts', array($this, 'add_sinfo_page_scripts_styles'));
		}
	}

	public function add_sinfo_page_scripts_styles()
	{
		if (isset($_GET['page']) && $_GET['page'] == 'side-sup-system-info-submenu-page') {
			//Settings Page CSS
			wp_register_style('side_sup_sinfo_css', plugins_url('css/admin-pages.css', __FILE__));
			wp_enqueue_style('side_sup_sinfo_css');
		}

	}

	/**
	 * System Info Page
	 *
	 * @since 1.0.0
	 */
	function System_Info_Page() {
?>
		<div class="side-sup-help-admin-wrap"> <a class="buy-extensions-btn" href="http://www.slickremix.com/downloads/category/sidebar-support/" target="_blank">
		<?php _e( 'Get Extensions Here!', 'sidebar-support' ); ?>
		</a>
		<h2>
		<?php _e( 'System Info', 'sidebar-support' ); ?>
		</h2>
		<p>
		<?php _e( 'Please click the box below and copy the report. You will need to paste this information along with your question in our', 'sidebar-support' ); ?>
		<a href="http://www.slickremix.com/support-forum/" target="_blank">
		<?php _e( 'Support Forum', 'sidebar-support' ); ?>
		</a>.
		<?php _e( 'Ask your question then paste the copied text below it.  To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'sidebar-support' ); ?>
		</p>
		<form action="<?php echo esc_url( admin_url( 'admin.php?page=side-sup-system-info-submenu-page' ) ); ?>" method="post" dir="ltr" >
		<textarea readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="side-sup-sysinfo" title="<?php _e( 'To copy the system info, click here then press Ctrl + C (PC) or Cmd + C (Mac).', 'sidebar-support' ); ?>">
### Begin System Info ###
		<?php
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version; ?>

SITE_URL:                 <?php echo site_url() . "\n"; ?>
Sidebar Support Version: <?php echo side_sup_version(). "\n"; ?>

-- Wordpress Configuration

WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>
Permalink Structure:      <?php echo get_option( 'permalink_structure' ) . "\n"; ?>
Active Theme:             <?php echo $theme . "\n"; ?>
PHP Memory Limit:         <?php echo ini_get( 'memory_limit' ) . "\n"; ?>
WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>

-- Webserver Configuration

PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

-- PHP Configuration:

Safe Mode:                <?php echo ini_get( 'safe_mode' ) ? "Yes" : "No\n"; ?>
Upload Max Size:          <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
Post Max Size:            <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
Upload Max Filesize:      <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
Time Limit:               <?php echo ini_get( 'max_execution_time' ) . "\n"; ?>
Max Input Vars:           <?php echo ini_get( 'max_input_vars' ) . "\n"; ?>
Allow URL File Open:      <?php echo ( ini_get( 'allow_url_fopen' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>
Display Erros:            <?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>

-- PHP Extensions:

FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen.'; ?><?php echo "\n"; ?>
cURL:                     <?php echo ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL.'; ?><?php echo "\n"; ?>

-- Active Plugins:

<?php $plugins = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $plugins as $plugin_path => $plugin ) {
			// If the plugin isn't active, don't show it.
			if ( ! in_array( $plugin_path, $active_plugins ) )
				continue;
			echo $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
		}
		if ( is_multisite() ) :
?>
-- Network Active Plugins:

		<?php
			$plugins = wp_get_active_network_plugins();
		$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

		foreach ( $plugins as $plugin_path ) {
			$plugin_base = plugin_basename( $plugin_path );

			// If the plugin isn't active, don't show it.
			if ( ! array_key_exists( $plugin_base, $active_plugins ) )
				continue;

			$plugin = get_plugin_data( $plugin_path );

			echo $plugin['Name'] . ' :' . $plugin['Version'] ."\n";
		}

		endif;

  if (is_plugin_active('side-sup-premium/side-sup-premium.php')) {
			$sidebar_support_premium_license_key = get_option('sidebar_support_premium_license_key');
?>
-- Premium License

Premium Active:           <?php echo isset($sidebar_support_premium_license_key) && $sidebar_support_premium_license_key !== '' ? 'Yes'. "\n" : 'No'. "\n"; } ?>

### End System Info ###</textarea>
		</form>
		<a class="side-sup-settings-admin-slick-logo" href="http://www.slickremix.com/support-forum/" target="_blank"></a> </div>
		<?php
	}
}//End Class