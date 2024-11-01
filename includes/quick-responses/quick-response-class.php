<?php namespace Sidebar_Support;
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Quick_Responses {
	public function __construct() {
		//Register Quick Response CPT
		add_action('init', array($this, 'quick_response_cpt'));
		//Response Messages
		add_filter('post_updated_messages', array($this, 'quick_response_updated_messages'));
		//Change Button Text
		add_filter('gettext',  array($this,'quick_response_set_button_text'), 20, 3);
		//Add Meta Boxes
		add_action('add_meta_boxes', array($this,'add_quick_response_metaboxes'));
		//Save Meta Box Info
		add_action('save_post', array($this,'save_custom_meta_box'), 10, 3);
		//Rename Submenu Item to Quick Responses
		add_filter('attribute_escape', array($this,'rename_side_sup_submenu_name'), 10, 2);
		//Add Shortcode
		add_shortcode('ss_quick_responses_list', array( $this, 'display_quick_responses_list'));

	}

	/**
	 * Create Quick Response cpt (Custom Post Type)
	 *
	 * @since 1.0.0
	 */
	public function quick_response_cpt() {
		$responses_cpt_args = array(
			'label' => __('Sidebar Support', 'sidebar-suppport'),
			'labels' => array (
				'menu_name' => __('Quick Responses', 'sidebar-suppport'),
				'name' => __('All Quick Responses', 'sidebar-suppport'),
				'singular_name' => __('Quick Response', 'sidebar-suppport'),
				'add_new' => __('Add Quick Response', 'sidebar-suppport'),
				'add_new_item' => __('Add New Quick Response', 'sidebar-suppport'),
				'edit_item' => __('Edit Quick Response', 'sidebar-suppport'),
				'new_item'  => __('New Quick Response', 'sidebar-suppport'),
				'view_item'  => __('View Quick Response', 'sidebar-suppport'),
				'search_items' => __('Search Quick Responses', 'sidebar-suppport'),
				'not_found' => __('No Quick Responses Found', 'sidebar-suppport'),
				'not_found_in_trash' => __('No Quick Responses Found In Trash', 'sidebar-suppport'),
			),

			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'capability_type' => 'post',
            'show_in_menu' => true,
            'show_in_nav_menus' => false,

			'capabilities' => array(
				'create_posts' => true, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
			),
			'map_meta_cap' => true, //Allows Users to still edit Payments
			'has_archive' => false,
			'hierarchical' => true,
			'query_var' => 'ss_quick_responses',

			'menu_icon' => '',
			'supports' => array ('title','editor','revisions'),
			'order' => 'DESC',
			// Set the available taxonomies here
			'taxonomies' => array('ss_qr_topics')
		);
		register_post_type( 'ss_quick_responses', $responses_cpt_args);
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
		if ( 'Quick Responses' !== $text ) {
			return $safe_text;
		}
		// We are on the main menu item now. The filter is not needed anymore.
		remove_filter( 'attribute_escape',  array($this,'rename_side_sup_submenu_name'));
		return 'Sidebar Support';
	}
	/**
	 * Add Payment Meta Boxes
	 *
	 * @param $messages
	 * @return mixed
	 * @since 1.0.0
     */
    public function quick_response_updated_messages($messages) {
		global $post, $post_ID;
		$messages['ss_quick_responses'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Quick Response updated.', 'sidebar-suppport'),
			2 => __('Custom field updated.', 'sidebar-suppport'),
			3 => __('Custom field deleted.', 'sidebar-suppport'),
			4 => __('Quick Response updated.', 'sidebar-suppport'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Response restored to revision from %s', 'sidebar-suppport'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Quick Response created.', 'sidebar-suppport'),
			7 => __('Quick Response saved.', 'sidebar-suppport'),
			8 => __('Quick Response submitted.', 'sidebar-suppport'),
			9 => __('Quick Response scheduled for: <strong>%1$s</strong>.', 'sidebar-suppport'),
			  // translators: Publish box date format, see http://php.net/date
			 // date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => __('Quick Response draft updated.', 'sidebar-suppport'),
		);
		return $messages;
	}
    /**
     * Set Edit Post buttons for Quick Responses CPT
     *
     * @param $translated_text
     * @param $text
     * @param $domain
     * @return mixed
     * @since 1.0.0
     */
    public function quick_response_set_button_text($translated_text, $text, $domain ) {
		$post_id = isset($_GET['post']) ? $_GET['post'] : '';
		 $custom_post_type = get_post_type($post_id);
			if(!empty($post_id) && $custom_post_type == 'side_sup_responses'){
				switch ($translated_text) {
					case 'Publish' :
						$translated_text = __( 'Save Quick Response', 'sidebar-suppport');
						break;
					case 'Update' :
						$translated_text = __( 'Update Quick Response', 'sidebar-suppport');
						break;
					case 'Save Draft' :
						$translated_text = __( 'Save Quick Response Draft', 'sidebar-suppport');
						break;
					case 'Edit Payment' :
						$translated_text = __( 'Edit Quick Response', 'sidebar-suppport');
						break;		
				}
			}
		return $translated_text;
	}
    /**
     * Add Quick Response Meta Boxes
     *
     * @since 1.0.0
     */
    public function add_quick_response_metaboxes() {
        $topics_class = new Topics();
        //Link Settings Meta Box
        add_meta_box('quick-response-settings-mb', __('Quick Response Settings', 'sidebar-suppport'),  array($this,'quick_response_settings_meta_box'), 'ss_quick_responses', 'side', 'high', null);
		//Topic Meta Box (first Remove default)
        remove_meta_box('ss_qr_topicsdiv', 'ss_quick_responses', 'side');
		add_meta_box('quick-responses-topics-mb', __('Topics (for Quick Responses)', 'sidebar-suppport'),  array($topics_class,'topic_meta_box'), 'ss_quick_responses', 'side', 'low', null);
	}
    public function quick_response_settings_meta_box($object) {
        wp_nonce_field(basename(__FILE__), 'quick-response-settings-meta-box-nonce');

        $show_title = get_post_meta($object->ID, 'quick_response_show_title', true);
        $meta_box = '<div class="side-sup-meta-wrap">';
        //Quick Link Status
        $meta_box .= '<p>';
        $meta_box .= '<label> '.__( 'Show Title on Front End:', 'sidebar-suppport' ).'</label>';
        $meta_box .= '<select name="quick_response_show_title" class="medium-text">';
        $meta_box .= '<option value="yes"'. (isset($show_title) && $show_title == 'yes' || !isset($show_title) ? 'selected="selected"' : '').'>'.__('Yes', 'sidebar-suppport').'</option>';
        $meta_box .= '<option value="no"'. (isset($show_title) && $show_title == 'no' ? 'selected="selected"' : '').'>'.__('No', 'sidebar-suppport').'</option>';
        $meta_box .= '</select>';
        $meta_box .= '</p>';

        $meta_box .= '</div>';
        // ECHO MetaBox
        echo $meta_box;
    }
    /**
     * Save Fields for Quick Responses
     *
     * @param $post_id
     * @param $post
     * @param $update
     * @return mixed
     * @since 1.0.0
     */
    public function save_custom_meta_box($post_id, $post, $update) {
        if (!isset($_POST['quick-response-settings-meta-box-nonce']) || !wp_verify_nonce($_POST['quick-response-settings-meta-box-nonce'], basename(__FILE__)))
            return $post_id;
	    //Can User Edit Post?
	    if(!current_user_can('edit_post', $post_id))
	        return $post_id;
		//Autosave
	    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
	        return $post_id;
	    //CPT Check
	    $slug = 'ss_quick_responses';
	    if($slug != $post->post_type)
	        return $post_id;
		//Default Field value	
	    $field_value = '';
	    // Field Array
		$meta_field_array = array(
		    'quick_response_show_title'
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