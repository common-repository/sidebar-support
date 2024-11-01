<?php namespace Sidebar_Support;
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Display_List {
    function __construct() {
        //Add Shortcode
        add_shortcode('side_sup_display_list', array( $this, 'display_lists_shortcode'));
    }
    public function display_feed_list($feed_type, $items_array, $section_title = ''){
        //Which List is it?
        switch($feed_type){
            case 'WordPress_plugins' :
            case 'WordPress_themes' :
                $feed_classes = 'side_sup_wordpress_feed';
                break;
            case 'GitHub' :
                $feed_classes = 'side_sup_github_feed';
                break;
            case 'BitBucket' :
                $feed_classes = 'side_sup_bitbucket_feed';
                break;
            case 'GitLab' :
                $feed_classes = 'side_sup_gitlab_feed';
                break;
        }
        $diplay_item = !empty($section_title) ? '<div class="side-sup-section-title">'.$section_title.'</div>' : '';
        if(!empty($items_array)){
            $diplay_item .= '<ul class="side_sup_feed_list '.$feed_classes.'">';
            foreach($items_array as $item){
                //Is Repo Public or Private?
                $is_private = isset($item['is_private']) && $item['is_private'] == true ? '<span title="'.__('Private Repository', 'sidebar-suppport').'" class="side-sup-private"></span>' : '<span title="'.__('Public Repository', 'sidebar-suppport').'" class="side-sup-public"></span>';
                //Check to make sure only ADMINS can see private Repos!

                //Start Item display
                $diplay_item .= '<li><div class="sidebar-support-h3"><span><a href="'.$item['main_url'].'">'.$item['name'].'</a></span>'.$is_private.'</div>';
                foreach ($item['buttons'] as $btn_name => $btn_url)
                    $diplay_item .= '<a class="side_sup_feed_btn" href="'.$btn_url.'" target="_blank">'.$btn_name.'</a>';
                $diplay_item .='</li>';
            }
            $diplay_item .= '</ul>';
        }
        else{
            $diplay_item = 'There are no Repositories/Plugins/Themes to display.';
        }
        return $diplay_item;
    }
    public function display_lists_shortcode($atts){
        $ss_atts =  shortcode_atts( array(
            'list' => '',
            'sortable' => 'no',
        ), $atts );
        return $this->display_the_list($ss_atts['list'], $ss_atts['sortable']);
    }
    public function display_quick_topic_item($topic = '', $sortable_list = 'no', $id = ''){
        if(!empty($id)){
            $topic = get_term_by('id',$id, 'ss_qr_topics');
        }
        $sortable_li_classes = $sortable_list == 'no' ? '' : 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';
        $item = '<li style="display: list-item;" class="side-sup-topic-item '.$sortable_li_classes.'" id="side-sup-menuItem_'.$topic->term_id.'" data-id="'.$topic->term_id.'" data-slug="'.$topic->slug.'" data-topic="'.$topic->name.'" data-parent="'.$topic->parent.'" >';
        $item .= '<div class="side-sup-menuDiv">';

        $item .= '<span title="Click to show/hide item editor" data-id="'.$topic->term_id.'" class="expandEditor ui-icon ui-icon-triangle-1-n"></span>';
        $item .= '<span class="itemTitle">'.$topic->name.'</span>';
        //Edit Topic Item
        $item .= $sortable_list == 'no' ? '' : '<a title="Edit Quick Item" href="'.get_edit_term_link($topic->term_id, 'ss_qr_topics').'" class="side-sup-edit-item" target="_blank">Edit</a>';
        //Edit Topic Item (Only allow removal for Sortable)
        $item .= $sortable_list == 'no' ? '' : '<a title="Delete Quick Item" href="javascript:;" class="side-sup-delete-category" data-id="'.$topic->term_id.'"><span title="Delete Quick Item" data-id="ss-topic-id'.$topic->term_id.'" class="deleteMenu ui-icon ui-icon-closethick">'. __('Trash', 'sidebar-support') .'</span> | </a>';
        $item .= '</div>';

        return $item;
    }
	public function display_quick_list_item($list_type , $sortable_list = 'no', $id = '', $topic_slug = ''){
        global $post;
        //Use get_post or Global Post
        $post = !empty($id) ? get_post($id) : $post;
        $topic = get_term_by('slug',$topic_slug, 'ss_qr_topics');

        //Data Topic check
        $data_topic = !empty($topic_slug) ? 'data-topic="'.$topic->term_id.'"' : '';
        $ajax_class = !empty($topic_slug) ? ' quick_item_ajax': '';
        $menu_div_class = !empty($topic_slug) ? ' new-item-handle': '';

        $sortable_li_classes = $sortable_list == 'no' ? '' : 'mjs-nestedSortable-branch mjs-nestedSortable-no-nesting';
        //Which List is it?
        switch($list_type){
            case 'quick_responses' :
                $list_type_classes = 'quick-response-item';
                $show_title = get_post_meta($post->ID, 'quick_response_show_title', true);
                    break;
            case 'quick_links' :
                $list_type_classes = 'quick-link-item';
                $link_input = get_post_meta($post->ID, 'side_sup_quick_link', true)  ;
                $link = isset($link_input) && !empty($link_input) ? $link_input : '' ;
                $target_option = get_post_meta($post->ID, 'side_sup_quick_link_target', true);
                $target = isset($target_option) && !empty($target_option) ? $target_option : '_blank' ;
                $show_title = get_post_meta($post->ID, 'quick_link_show_title', true);
                    break;
        }
        //List Item
		$item = '<li style="display: list-item;" class="side-sup-quick-item '.$list_type_classes.' '.$sortable_li_classes . $ajax_class.'" id="side-sup-menuItem_'.$post->ID.'" '.$data_topic.' data-post="'.$post->post_title.'" >';
            $item .= '<div class="side-sup-menuDiv'.$menu_div_class.'">';
                $item .= '<span title="Click to show/hide item editor" data-id="'.$post->ID.'" class="expandEditor ui-icon ui-icon-triangle-1-n"></span>';

                $item .= $sortable_list == 'no' && $list_type !== 'quick_links' && isset($show_title) && $show_title == 'yes' ? '<span data-id="'.$post->ID.'" class="itemTitle">'.$post->post_title.'</span>' : '';
                $item .= $sortable_list !== 'no' && $list_type !== 'quick_links' ? '<span data-id="'.$post->ID.'" class="itemTitle">'.$post->post_title.'</span>' : '';

                $item .= $sortable_list == 'no' && $list_type == 'quick_links' ? '<a class="side-sup-quick-link-title" href="'.$link.'" target="'.$target.'"><span data-id="'.$post->ID.'" class="itemTitle">'.$post->post_title.'</span></a>' : '';
                $item .= $sortable_list !== 'no' && $list_type == 'quick_links' ? '<span data-id="'.$post->ID.'" class="itemTitle">'.$post->post_title.'</span>' : '';

                //Edit Quick Item
                $item .=  $sortable_list == 'no' ? '' : '<a title="Edit Quick Item" href="'.get_edit_post_link($post->ID, '').'" class="side-sup-edit-item" target="_blank">Edit</a>';

                //Edit Quick Item (Only allow removal for Sortable)
                $item .= $sortable_list == 'no' ? '' : '<a title="Delete Quick Item" href="javascript:;" class="side-sup-delete-item" id="ss-quick-item-id-'.$post->ID.'" data-id="'.$post->ID.'" data-nonce="'.wp_create_nonce('side_sup_delete_quick_item_nonce').'"><span title="Delete Quick Item"  class=" ui-icon ui-icon-closethick">'. __('Trash', 'sidebar-support') .'</span> | </a>';
                //Content
                if($list_type == 'quick_links' && $sortable_list == 'yes' || $list_type == 'quick_responses'){
                    $item .=  '<div id="side-sup-menuEdit'.$post->ID.'" class="side-sup-menuEdit hidden">';
                    $item .= $list_type == 'quick_links' ? '<p><a class="side-sup-quick-link-title" href="'.$link.'" target="'.$target.'">'.$link.'</a></p>' : '<p>'.$post->post_content.'</p>';
                    $item .= '</div>';
                }
        if(is_admin()){
           $item .= ' <div class="fa fa-check-circle fa-3x fa-fw sidebar-sup-success"></div>';
        }
        else {
            $item .= '<div class="side-sup-copy-icon" data-id="'.$post->ID.'"><div class="fa fa-check-circle fa-3x fa-fw sidebar-sup-success"></div></div>';
        }

            $item .= '</div>';
        $item .= '</li>';

		return $item;
	}
    function list_level($list_type, $topics, $level= 0, $sortable_ul_classes = '', $sortable_list) {
        $list ='';

        foreach($topics as $topic) if ($topic->parent == $level ) {
                $topics_class = new Topics();
                //Setup Needed Classes
                $error_check = new Error_Handler();

                //Get Topic list Item
                $list .= $this->display_quick_topic_item($topic, $sortable_list);
                $quick_items = $topics_class->get_quick_items_by_topic($list_type, $topic->term_id);

                //Check Quick Items and make sure no errors or not empty (check funct in error-handler.php)
                $list_check = $error_check->check_quick_items($list_type, $quick_items);
                if (!empty($list_check)) {
                    //  $list.= '<li class="error-message">'.$list_check[1].'</li>';
                } else {
                    //Build Quick Items
                    $list .= '<ul class="quick-item-list">';
                    //Return message if check doesn't pass
                        //Display Quick Items (Responses, Links or Docs...etc)
                        if (is_object($quick_items)) {
                            if ($quick_items->have_posts()) : while ($quick_items->have_posts()) : $quick_items->the_post();
                                //Get Response list Item
                                $list .= $this->display_quick_list_item($list_type, $sortable_list);
                            endwhile; endif;
                        }
                    $list .= '</ul>';
                }
                $list .= $this->list_level($list_type, $topics, $topic->term_id, $sortable_ul_classes, $sortable_list);
                $list .= '</li>';//Topic LI
        }

        if(isset($list) && !empty($list)){
            return $level == 0 ? $list : '<ul class="side-sup-topic-item '.$sortable_ul_classes.'">'.$list.'</ul>';
        }
    }
    public function display_no_topic_list($list_type, $sortable_list = 'no', $sortable_ul_classes) {
        $topics_class = new Topics();
        $no_topic_quick_items = $topics_class->get_quick_items_without_topic($list_type);

        switch($list_type){
            case 'quick_responses' :
                $no_topics_classes = 'no-topics-quick-responses ';
                break;
            case 'quick_links' :
                $no_topics_classes = 'no-topics-quick-links ';
                break;
        }

        //Show No topic Items
        $list= '';
        if($sortable_list == 'yes'){
            $list .= '<li id="no-topic-quick-items" class="mjs-nestedSortable-disabled disablesort">';

            $list .= '<div class="no-topic-quick-items-header">'.__('Items below have no Topics.', 'sidebar-support').'</div>';

                    if (is_plugin_active('sidebar-support-premium/sidebar-support-premium.php')) {
                        $list .= '<div class="no-topic-quick-items-description">' . __('These items will not display on front end unless they are in a Topic. Drag items into any Topic above.', 'sidebar-support') . '</div>';
                    }
                    else {
                        $list .= '<div class="no-topic-quick-items-description">'.__('These items will not display on front end unless they are in a Topic. Click the Edit link next to an item you want to attach to a Topic.', 'sidebar-support').'</div>';
                    }

            //Build Quick Items
                    $list .= '<ul class="no-topic-ul-list '.$no_topics_classes.$sortable_ul_classes.'">';
                         if(isset($no_topic_quick_items)){
                            //Return message if check doesn't pass
                            //Display Quick Items (Responses, Links or Docs...etc)
                            if (is_object($no_topic_quick_items)) {
                                if ($no_topic_quick_items->have_posts()) : while ($no_topic_quick_items->have_posts()) : $no_topic_quick_items->the_post();
                                    //Get Response list Item
                                    $list .= $this->display_quick_list_item($list_type, $sortable_list);
                                endwhile; endif;
                            }

                        }
                    $list .= '</ul>';

            $list .= '</li>';
        }
        return $list;
    }
	public function display_the_list($list_type, $sortable_list = 'no'){
		//Setup Needed Classes
		$error_check = new Error_Handler();
        $topics_class = new Topics();
		//Build Topics (This gets ordered topics and Topics without and an order MetaKey and merges them)
        $topics = $topics_class->get_topics_list($list_type);

        $list = '';

        //Check if Topics are empty
        $topics_check = $error_check->check_topics($topics);
        if (!empty($topics_check)) {$list = '<div>'.$topics_check[1].'</div>'; }

        //Sortable Classes
        $sortable_ul_classes = $sortable_list == 'no' ? '' :  'ui-sortable mjs-nestedSortable-branch mjs-nestedSortable-expanded' ;

		$list .= '<ul class="side-sup-ul '.$list_type.'_sortable '.$sortable_ul_classes.'">';

        //$list .=  $sortable_list == 'no' ? '' : '<li style="display: none"></li>' ;

        if (isset($topics) && !empty($topics)) {
            $list .= $this->list_level($list_type, $topics, '0', $sortable_ul_classes, $sortable_list);

            //No Topic Items
            if ($sortable_list == 'yes') {
                $list .= $this->display_no_topic_list($list_type, $sortable_list, $sortable_ul_classes);
            }
        }
		$list .= '</ul>';

		return $list;
	}
}
?>