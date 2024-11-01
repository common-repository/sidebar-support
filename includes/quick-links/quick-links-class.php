<?php namespace Sidebar_Support;
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Quick_Links {
	public function __construct() {
		//Register Quick Link CPT
		add_action('init', array($this, 'quick_link_cpt'));
		//Quick Links Messages
		add_filter('post_updated_messages', array($this, 'quick_link_updated_messages'));
		//Change Button Text
		add_filter('gettext',  array($this,'quick_link_set_button_text'), 20, 3);
		//Add Meta Boxes
		add_action('add_meta_boxes', array($this,'add_quick_link_metaboxes'));
		//Save Meta Box Info
		add_action('save_post', array($this,'save_custom_meta_box'), 10, 3);
		//Rename Submenu Item to Quick Links
		add_filter('attribute_escape', array($this,'rename_side_sup_submenu_name'), 10, 2);
		//Add Shortcode
		add_shortcode('ss_quick_links_list', array( $this, 'display_quick_links_list'));
		//Add Styles and Scripts to Quick Links edit page
		add_action('admin_enqueue_scripts', array( $this,'add_quick_links_scripts_styles'), 10, 1 );
	}
	/**
	 * Create Quick Link CPT (Custom Post Type)
	 *
	 * @since 1.0.0
	 */
	public function quick_link_cpt() {
		$responses_cpt_args = array(
			'labels' => array (
				'menu_name' => __('Quick Links', 'sidebar-suppport'),
				'name' => __('All Quick Links', 'sidebar-suppport'),
				'singular_name' => __('Quick Link', 'sidebar-suppport'),
				'add_new' => __('Add Quick Link', 'sidebar-suppport'),
				'add_new_item' => __('Add New Quick Link', 'sidebar-suppport'),
				'edit_item' => __('Edit Quick Link', 'sidebar-suppport'),
				'new_item'  => __('New Quick Link', 'sidebar-suppport'),
				'view_item'  => __('View Quick Link', 'sidebar-suppport'),
				'search_items' => __('Search Quick Links', 'sidebar-suppport'),
				'not_found' => __('No Quick Links Found', 'sidebar-suppport'),
				'not_found_in_trash' => __('No Quick Links Found In Trash', 'sidebar-suppport'),
			),
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => false,
			'capability_type' => 'post',
			'capabilities' => array(
				'create_posts' => true, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
			),
			'map_meta_cap' => true, //Allows Users to still edit Payments
			'has_archive' => false,
			'hierarchical' => true,
			'query_var' => 'ss_quick_links',
			'menu_icon' => '',
			'supports' => array ('title','revisions'),
			'order' => 'DESC',
			// Set the available taxonomies here
			'taxonomies' => array('ss_qr_topics')
		);
		register_post_type( 'ss_quick_links', $responses_cpt_args);
	}
	/**
	 * Rename Submenu Item of Sidebar Support
	 *
	 * @param $safe_text
	 * @param $text
	 * @return string
     * @since 1.0.0
     */
    function rename_side_sup_submenu_name($safe_text, $text ){
		if ( 'Quick Links' !== $text ) {
			return $safe_text;
		}
		// We are on the main menu item now. The filter is not needed anymore.
		remove_filter( 'attribute_escape',  array($this,'rename_side_sup_submenu_name'));
		return 'Sidebar Support';
	}

	/**
	 * Add Styles and Scripts to Quick Links edit page
	 *
	 * @param $hook
	 * @since 1.0.0
     */
	function add_quick_links_scripts_styles($hook) {
		global $post;
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
			if ( 'ss_quick_links' === $post->post_type ) {
				wp_register_style('side-sup-quick-links', plugins_url('../../admin/css/admin-pages.css', __FILE__));
				wp_enqueue_style('side-sup-quick-links');
			}
		}
	}

	/**
	 * Add Payment Meta Boxes
	 *
	 * @param $messages
	 * @return mixed
	 * @since 1.0.0
     */
    public function quick_link_updated_messages($messages) {
		global $post, $post_ID;
		$messages['ss_quick_links'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Quick Link updated.', 'sidebar-suppport'),
			2 => __('Custom field updated.', 'sidebar-suppport'),
			3 => __('Custom field deleted.', 'sidebar-suppport'),
			4 => __('Quick Link updated.', 'sidebar-suppport'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Response restored to revision from %s', 'sidebar-suppport'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Quick Link created.', 'sidebar-suppport'),
			7 => __('Quick Link saved.', 'sidebar-suppport'),
			8 => __('Quick Link submitted.', 'sidebar-suppport'),
			9 => __('Quick Link scheduled for: <strong>%1$s</strong>.', 'sidebar-suppport'),
			  // translators: Publish box date format, see http://php.net/date
			 // date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => __('Quick Link draft updated.', 'sidebar-suppport'),
		);
		return $messages;
	}

    /**
     * Set Edit Post buttons for Quick Links CPT
     *
     * @param $translated_text
     * @param $textu
     * @param $domain
     * @return mixed
     * @since 1.0.0
     */
    public function quick_link_set_button_text($translated_text, $text, $domain ) {
		$post_id = isset($_GET['post']) ? $_GET['post'] : '';
		 $custom_post_type = get_post_type($post_id);
			if(!empty($post_id) && $custom_post_type == 'side_sup_responses'){
				switch ($translated_text) {
					case 'Publish' :
						$translated_text = __( 'Save Quick Link', 'sidebar-suppport');
						break;
					case 'Update' :
						$translated_text = __( 'Update Quick Link', 'sidebar-suppport');
						break;
					case 'Save Draft' :
						$translated_text = __( 'Save Quick Link Draft', 'sidebar-suppport');
						break;
					case 'Edit Payment' :
						$translated_text = __( 'Edit Quick Link', 'sidebar-suppport');
						break;		
				}
			}
		return $translated_text;
	}
    /**
     * Add Quick Link Meta Boxes
     *
     * @since 1.0.0
     */
    public function add_quick_link_metaboxes() {
        $topics_class = new Topics();
        //Link Info Meta Box
        add_meta_box('quick-link-info-mb', __('Quick Link Info', 'sidebar-suppport'),  array($this,'quick_link_info_meta_box'), 'ss_quick_links', 'normal', 'high', null);
        //Topic Meta Box (first Remove DEFAULT)
        remove_meta_box('ss_qr_topicsdiv', 'ss_quick_links', 'side');
        add_meta_box('quick-links-topics-mb', __('Topics (for Quick Links)', 'sidebar-suppport'),  array($topics_class,'topic_meta_box'), 'ss_quick_links', 'side', 'low', null);
    }


	public function quick_link_info_meta_box($object) {
		wp_nonce_field(basename(__FILE__), 'quick-link-info-meta-box-nonce');

        $quick_link = get_post_meta($object->ID, 'side_sup_quick_link', true);
       // $url_error = $this->validate_input_url($quick_link);
		$meta_box = '<div class="side-sup-link-info-meta-wrap">';
      //  $meta_box .= !empty($url_error) ? '<div class="side-sup-error-msg">'.$url_error.'</div>' : '';
		//Client First Name
		$meta_box .= '<p><label for="side_sup_quick_link">'.__( 'Link: ', 'sidebar-suppport').' </label>';
		$meta_box .= '<input name="side_sup_quick_link" type="text" value="'.$quick_link.'"></p>';
        //Client First Name
        $meta_box .= '<p>';
        $meta_box .= '<label for="side_sup_quick_link_target"> '.__( 'Open In: ', 'sidebar-suppport' ).'</label>';
        $meta_box .= '<select name="side_sup_quick_link_target" class="medium-text">';

        $meta_box .= '<option value="_blank"'. (get_post_meta($object->ID, 'side_sup_quick_link_target', true) == '_blank' ? 'selected="selected"' : '').'>'.__( 'New Tab', 'sidebar-suppport' ).'</option>';
        $meta_box .= '<option value="_self"'. (get_post_meta($object->ID, 'side_sup_quick_link_target', true) == '_self' ? 'selected="selected"' : '').'>'.__( 'Same Tab', 'sidebar-suppport' ).'</option>';

        $meta_box .= '</select>';
        $meta_box .= '</p>';
        $meta_box .= '</div>';


		echo $meta_box;
	}
    public function validate_input_url($url) {
        if (empty($url)) {
            return  __( 'Please Enter a URL', 'sidebar-suppport');
        } else {
            $input_url = sanitize_text_field( $url );
            // check if URL address syntax is valid
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$input_url)) {
                return __( 'Please enter a Valid URL', 'sidebar-suppport');
            }
            else {return;}
        }
    }
    /**
     * Save Fields for Quick Links
     *
     * @param $post_id
     * @param $post
     * @param $update
     * @return mixed
     * @since 1.0.0
     */
    public function save_custom_meta_box($post_id, $post, $update) {

		//Link Info Metabox Nonce
		if (!isset($_POST['quick-link-info-meta-box-nonce']) || !wp_verify_nonce($_POST['quick-link-info-meta-box-nonce'],basename(__FILE__)))
			return $post_id;
	    //Can User Edit Post?
	    if(!current_user_can('edit_post', $post_id))
	        return $post_id;
		//Autosave
	    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
	        return $post_id;
	    //CPT Check
	    $slug = 'ss_quick_links';
	    if($slug != $post->post_type)
	        return $post_id;
		//Default Field value	
	    $field_value = '';
	    // Field Array
	    $meta_field_array = array(
		    //'quick_link_status',
			'side_sup_quick_link',
            'side_sup_quick_link_target',
	    );

	    //Save Each Field Function
	    foreach ($meta_field_array as $field_name) {
		    if(isset($_POST[$field_name])) {
		        $field_value = $_POST[$field_name];
		    }
		    update_post_meta($post_id, $field_name, $field_value);
	    }
	}
}
?>