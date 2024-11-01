<?php namespace Sidebar_Support;
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Topics {
	public function __construct() {
		//Register Topics
		add_action('init', array($this, 'register_topics'),1);

        //TAXONOMY PAGE STUFF
        add_action( 'ss_qr_topics_add_form_fields', array($this,'ss_qr_topics_add_meta_fields'), 10, 2 );
        add_action( 'ss_qr_topics_edit_form_fields', array($this,'ss_qr_topics_edit_meta_fields'), 10, 2 );
        //Save Topic Custom field
        add_action( 'created_ss_qr_topics', array($this,'ss_qr_topics_save_taxonomy_meta'), 10, 2 );
        add_action( 'edited_ss_qr_topics', array($this,'ss_qr_topics_save_taxonomy_meta'), 10, 2 );
        //Top Custom Field Coloumn
        add_filter( 'manage_edit-ss_qr_topics_columns', array($this,'ss_qr_topics_add_field_columns') );
        add_filter( 'manage_ss_qr_topics_custom_column', array($this,'ss_qr_topics_add_field_column_contents'), 10, 3 );
        
	}
	/**
	 * Register Sidebar Support Topics
	 *
	 * @since 1.0.0
     */
    function register_topics() {
		$quick_link_tax_args = array(
			'labels' => array(
				'name' => _x( __('Topics', 'sidebar-support'), 'ss_qr_topics' ),
				'singular_name' => _x( __('Topic', 'sidebar-support'), 'ss_qr_topics' ),
				'search_items' => _x( __('Search Topics', 'sidebar-support'), 'ss_qr_topics' ),
				//'popular_items' => _x( 'Popular Topics', 'ss_qr_topics' ),
				'all_items' => _x( __('All Topics', 'sidebar-support'), 'ss_qr_topics' ),
				'parent_item' => _x( __('Parent Topic', 'sidebar-support'), 'ss_qr_topics' ),
				'parent_item_colon' => _x( __('Parent Topic', 'sidebar-support'), 'ss_qr_topics' ),
				'edit_item' => _x( __('Edit Topic', 'sidebar-support'), 'ss_qr_topics' ),
				'update_item' => _x( __('Update Topic', 'sidebar-support'), 'ss_qr_topics' ),
				'add_new_item' => _x( __('Add New Topic', 'sidebar-support'), 'ss_qr_topics' ),
				'new_item_name' => _x( __('New Topic', 'sidebar-support'), 'ss_qr_topics' ),
				'separate_items_with_commas' => _x( __('Separate Topics with commas', 'sidebar-support'), 'ss_qr_topics' ),
				'add_or_remove_items' => _x( __('Add or remove Topics', 'sidebar-support'), 'ss_qr_topics' ),
				'choose_from_most_used' => _x( __('Choose from the most used Topics', 'sidebar-support'), 'ss_qr_topics' ),
				'menu_name' => _x( __('Topics', 'sidebar-support'), 'ss_qr_topics' ),
			),
			'public' => false,
			'show_in_nav_menus' => false,
			'show_admin_column'     => false,
			'show_ui' => true,
			//'show_tagcloud' => true,
			'hierarchical' => true,
			'update_count_callback' => '_update_post_term_count',
			'rewrite' => true,
			'query_var' => 'ss_qr_topics'
		);
		register_taxonomy('ss_qr_topics', array('ss_quick_responses', 'ss_quick_links'), $quick_link_tax_args);
	}
   
    //Add New Topic custom field
    function ss_qr_topics_add_meta_fields( $taxonomy ) {
        ?>
        <div class="form-field term-group">
            <label for="topic_placement"><?php _e( 'Include Topic In:', 'sidebar-support' ); ?></label>
            <p><input name="side_sup_topic_placement_responses" class="side-sup-settings-admin-input" type="checkbox" checked="checked" value="1"/>
            <?php _e('Quick Responses', 'sidebar-support'); ?></p>
            <p><input name="side_sup_topic_placement_links" class="side-sup-settings-admin-input" type="checkbox" checked="checked" value="1" />
            <?php _e('Quick Links', 'sidebar-support'); ?></p>
        </div>
        <?php
    }
    //Add New Topic custo field
    function ss_qr_topics_edit_meta_fields( $term, $taxonomy ) {
        ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="topic_placement"><?php _e( 'Include Topic In:', 'sidebar-support' ); ?></label>
            </th>
            <td>
                <input name="side_sup_topic_placement_responses" type="hidden" value="0"/>
                <input name="side_sup_topic_placement_responses" class="side-sup-settings-admin-input" type="checkbox" value="1" <?php checked('1', get_term_meta($term->term_id, 'side_sup_topic_placement_responses', true) ,true);?>/>
                <?php _e('Quick Responses', 'sidebar-support'); ?><br/>
                <input name="side_sup_topic_placement_links" type="hidden" value="0"/>
                <input name="side_sup_topic_placement_links" class="side-sup-settings-admin-input" type="checkbox" value="1" <?php checked('1', get_term_meta($term->term_id, 'side_sup_topic_placement_links', true) ,true);?>/>
                <?php _e('Quick Links', 'sidebar-support'); ?>
            </td>
        </tr>
        <?php
    }
    function ss_qr_topics_save_taxonomy_meta( $term_id, $tag_id ) {
        if( isset( $_POST['side_sup_topic_placement_responses'] ) ) {
            update_term_meta( $term_id, 'side_sup_topic_placement_responses', esc_attr( $_POST['side_sup_topic_placement_responses'] ) );
        }
        if( isset( $_POST['side_sup_topic_placement_links'] ) ) {
            update_term_meta( $term_id, 'side_sup_topic_placement_links', esc_attr( $_POST['side_sup_topic_placement_links'] ) );
        }
    }

    function ss_qr_topics_add_field_columns( $columns ) {
        $columns['topic_placement'] = __( 'Include Topics In:', 'my-plugin' );
        unset($columns['posts']);
        //unset($columns['description']);

        return $columns;
    }
    function ss_qr_topics_add_field_column_contents( $content, $column_name, $term_id ) {
        switch( $column_name ) {
            case 'topic_placement' :
                $topic_placement = array();
                $responses = get_term_meta($term_id, 'side_sup_topic_placement_responses', true);
                $links = get_term_meta($term_id, 'side_sup_topic_placement_links', true);

                if(isset($responses) && $responses == '1'){
                    $topic_placement[] = 'Quick Responses';
                }
                if(isset($links) && $links == '1'){
                    $topic_placement[] = 'Quick Links';
                }
                $final_topic_placement = implode(', ',$topic_placement);

                $content = !empty($final_topic_placement) ? $final_topic_placement : 'not included';
                break;
        }

        return $content;
    }
    /**
     * Get Quick Items By Topic
     *
     * @param $list_type (quick_responses, quick_links)
     * @param $topic
     * @return \WP_Query (array)
     * @since 1.0.0
     */
    public function get_quick_items_by_topic($list_type, $topic){
        //Which List is it?
        switch($list_type){
            //Quick Responses
            case 'quick_responses' :
                //Build Quick Responses
                $quick_items_cpt = 'ss_quick_responses';
                break;
            //Quick Links
            case 'quick_links' :
                $quick_items_cpt = 'ss_quick_links';
                break;
        }
        $args = array(
            'post_type' => $quick_items_cpt,
            //'posts_per_page' => $limit_designs,
            'post_status'   => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby' => 'menu_order date',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'ss_qr_topics',
                    'field'    => 'ID',
                    'terms'   => array($topic),
                    //Need to keep posts from showing in every term
                    'include_children' => false
                ),
            ),
            'posts_per_page' => 100000,
        );
        $quick_items = new \WP_Query($args);

        return !empty($quick_items->posts) ? $quick_items : NULL;
    }

	public function get_quick_items_without_topic($list_type){
		//Which List is it?
		switch($list_type){
			//Quick Responses
			case 'quick_responses' :
				//Build Quick Responses
				$quick_items_cpt = 'ss_quick_responses';
				break;
			//Quick Links
			case 'quick_links' :
				$quick_items_cpt = 'ss_quick_links';
				break;
		}
		$args = array(
			'post_type' => $quick_items_cpt,
			//'posts_per_page' => $limit_designs,
			'post_status'   => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby' => 'title',
			'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'ss_qr_topics',
					'field'    => 'ID',
					'terms'   => get_terms('ss_qr_topics', array( 'fields' => 'ids'  ) ),
					//Need to keep posts from showing in every term
					'include_children' => false,
					'operator' => 'NOT IN',
				),
			),
            'posts_per_page' => 100000,
		);
		$quick_items = new \WP_Query($args);

		return !empty($quick_items->posts) ? $quick_items : NULL;
	}
	/**
	 * Get Topics
     * get sidebar support topics (taxonomy)
	 *
	 * @param string $order
	 * @param null $offset
	 * @param string $per_page Set amount per page
	 * @return mixed (array || boolean)
	 * @since 1.0.0
	 */
	public function get_topics($hide_empty = false){
		$client_args = array(
            'hide_empty' => $hide_empty,
            'orderby'  => 'id',
            'order' => 'ASC',
            'number' => 0,
		);
		$topics = get_terms( 'ss_qr_topics', $client_args);

		return !empty($topics) ? $topics : NULL;
	}
    /**
     * Get Topics By List Type
     * List types: quick_response, quick_links
     *
     * @param string $order
     * @param null $offset
     * @param string $per_page Set amount per page
     * @return mixed (array || boolean)
     * @since 1.0.0
     */
    public function get_topics_by_list_type($list_type, $hide_empty = false){
        $client_args = array(
            'hide_empty' => $hide_empty,
            //If topics_order Term Meta does not exists it will order by name
            //Meta_key and Orderby metavalue are only available in wordpress 4.5 and beyond.
            'meta_key' => $list_type.'_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'number' => 0,
		);
		$topics = get_terms( 'ss_qr_topics', $client_args);

		return !empty($topics) ? $topics : NULL;
	}
    public function get_topics_list($list_type){
        $topics_by_list = $this->get_topics_by_list_type($list_type);
        $no_meta_topics = $this->get_topics();

        if($no_meta_topics && $topics_by_list) {
            $topics = array_merge($topics_by_list, $no_meta_topics);
        }
        else{
            //Check if Topics by list type is empty if so just use topics
            $topics = $topics_by_list !== NULL ? $topics_by_list : $no_meta_topics;
        }
            if($topics){
                // walk through array
                $id_list = array() ;
                foreach ($topics as $key => $arr) {
                    //Which List is it?
                    switch($list_type){
                        case 'quick_responses' :
                            $topic_placement = get_term_meta($arr->term_id, 'side_sup_topic_placement_responses', true);
                            break;
                        case 'quick_links' :
                            $topic_placement = get_term_meta($arr->term_id, 'side_sup_topic_placement_links', true);
                            break;
                        default:
                            $topic_placement = false;
                            break;
                    }

                    //Check for duplicates
                    if (in_array($arr->term_id, $id_list, true) || $topic_placement !== '1' || !isset($topic_placement)){
                        //Unset Duplicates
                        unset($topics[$key]);
                    }
                    $id_list[] = $arr->term_id;
                }
            }
        return !empty($topics) ? $topics : '';
    }
    public function topic_meta_box() {
        global $post;

        $ss_post_type = $post->post_type;
        switch($ss_post_type){
            case 'ss_quick_responses':
                $list_type = 'quick_responses';
                break;
            case 'ss_quick_links':
                $list_type = 'quick_links';
                break;
        }

        //Get taxonomy and terms
        $taxonomy = 'ss_qr_topics';

        //Set up the taxonomy object and get terms
        $tax = get_taxonomy($taxonomy);
        $terms = $this->get_topics_list($list_type);

        //Name of the form
        $name = 'tax_input[' . $taxonomy . ']';

        //Get current and popular terms

        $postterms = get_the_terms( $post->ID,$taxonomy );
        $current = ($postterms ? array_pop($postterms) : false);
        $current = ($current ? $current->term_id : 0);
        ?>

        <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

            <!-- Display tabs-->
            <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
                <li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
            </ul>

            <!-- Display taxonomy terms -->
            <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
                <ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
                    <?php   foreach($terms as $term){
                        $id = $taxonomy.'-'.$term->term_id;
                        echo "<li id='$id'><label class='selectit'>";
                        echo "<input type='radio' id='in-$id' name='{$name}'".checked($current,$term->term_id,false)."value='$term->term_id' />$term->name<br />";
                        echo "</label></li>";
                    }?>
                </ul>
            </div>

        </div>
        <?php


    }
    // This spits out the select options for our custom das_categories taxonomy
    function topic_by_list_type_dropdown($list_type) {
        //Build Topics (This gets ordered topics and Topics without and an order MetaKey and merges them)
        $topics = $this->get_topics_list($list_type);

        echo '<p class="side-sup-select-topics-category" '.(!$topics ? 'style="display:none"' : '').'>';
            echo '<label >' . __('Select Existing Topic Name', 'sidebar-support') . '</label> <br/>';
            echo '<select name="%s" class="postform" id="ss_qr_topics">', __('Topics', 'sidebar-support');


                echo '<option value="">';

                _e('Please Select', 'sidebar-support');
                echo '</option>';
            if ($topics) {
                foreach ($topics as $topic) {
                    printf('<option value="%s">%s</option>', esc_attr($topic->slug), esc_html($topic->name));
                }
            }
            print('</select>');
        echo '</p>';
    }




}
?>