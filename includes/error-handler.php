<?php namespace Sidebar_Support;
class Error_Handler {
    //Solution Text
    public $solution_text = 'Here are some possible solutions to fix this.';
	//Learn More
	public $learn_more_text = 'Here is how you can do this.';
	function __construct() {
	}
    /**
     * Check Topics for errors or if they exist
     *
     * @param null $topics
     * @since 1.0.0
     */
    function check_topics($topics = NULL){
        try {
            //If Empty
            if(empty($topics) || $topics == NULL){

                    if(isset($_GET['tab']) && $_GET['tab'] == 'quick_links' ) {
                        if (is_admin()) {
                             throw new \Exception('<div class="side-sup-error-notice ss-create-quick-links">' . __('No Topics Found. To get started click here or click the <strong>Create New Quick Link</strong> button above.<br/>Visit the Settings Page to style your Menu. ', 'sidebar-suppport') . '</div>');
                        }
                        else {
                            throw new \Exception('<div class="side-sup-error-notice ss-create-quick-links">' . __('No Topics with Responses Found. Please <a href="'.admin_url('edit.php?post_type=ss_quick_responses&page=side-sup-sidebar-builder-page&quicklinks=open&tab=quick_links').'">Click here to get started</a>.', 'sidebar-suppport') . '</div>');
                        }
                    }
                    else {
                        if (is_admin()) {
                            throw new \Exception('<div class="side-sup-error-notice ss-create-quick-response">' . __('No Topics Found. To get started click here or click the <strong>Create New Quick Response</strong>  button above.<br/>Visit the Settings Page to style your Menu. ', 'sidebar-suppport') . '</div>');
                        } else {
                            throw new \Exception('<div class="side-sup-error-notice ss-create-quick-response">' . __('No Topics with Links Found. Please <a href="' . admin_url('edit.php?post_type=ss_quick_responses&page=side-sup-sidebar-builder-page&quickresponse=open&tab=quick_responses') . '">Click here to get started</a>.', 'sidebar-suppport') . '</div>');
                        }
                    }

                }
        } catch (\Exception $e) {
            return array(true, $e->getMessage());
        }
    }
    /**
     * Check Quick Items (Responses, Links or Docs...etc) for errors or if they exist
     *
     * @param null $quick_items
     * @since 1.0.0
     */
    function check_quick_items($list_type, $quick_items = NULL){
        try {
            //If Empty
            if(empty($quick_items) || $quick_items == NULL){
                //Which List is it?
                switch($list_type){
                    //Quick Responses
                    case 'quick_responses' :
                        //Build Quick Responses
                        $quick_item_txt = 'No Quick Responses Found. ';
                        break;
                    //Quick Links
                    case 'quick_links' :
                        $quick_item_txt = 'No Quick Links Found. ';
                        break;
                    //Quick Docs
                    case 'quick_docs' :
                        $quick_item_txt = 'No Quick Docs Found. ';
                        break;
                }
                //Throw Error
                throw new \Exception('<div class="side-sup-error-notice>'.__($quick_item_txt, 'sidebar-suppport').'<a style="color:red !important;" href="https://www.slickremix.com/docs/quick-response-error-messages/#error-803" target="_blank">'.$this->learn_more_text.'</a></div>');
            }
        } catch (\Exception $e) {
            return array(true, $e->getMessage());
        }
    }
    /**
     * BitBuck Token Check
     *
     * @param null $topics
     * @since 1.0.0
     */
    function check_auth($feed, $checked){
        try {
            //Which List is it?
            switch($feed){
                //Quick Responses
                case 'GitHub' :
                    //Build Quick Responses
                    $feed_name = 'GitHub';
                    $solution_link = '';
                    break;
                //Quick Links
                case 'BitBucket' :
                    $feed_name = 'BitBucket';
                    $solution_link = '';
                    break;
                //Quick Docs
                case 'GitLab' :
                    $feed_name = 'GitLab';
                    $solution_link = '';
                    break;
            }
            //If Empty
            if($checked == false){
                throw new \Exception('<div style="clear:both; padding:15px 0;">'.__('Access Token validation did not work. ', 'sidebar-suppport').'<a style="color:red !important;" href="'.$solution_link.'" target="_blank">'.$this->learn_more_text.'</a></div>');
            }
        } catch (\Exception $e) {
            return array(true, $e->getMessage());
        }
    }
}
?>