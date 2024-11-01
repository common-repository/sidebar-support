<?php namespace Sidebar_Support;
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Class API_Feed_Fetch
 * @package Sidebar_Support
 */
class API_Feed_Fetch {
    //Prefix to set for transients in Database (must use underscores only)
    public $trans_prefix = 'side_sup_';
    //used to set Transient Cache time.
    public $trans_cache_time = 900;

    /**
     * Create endpoint by using endpoint url and adding any parameters
     *
     * @param $endpoint_array
     * @return string
     * @since
     */
    function create_feed_url($endpoint_array) {
        //Endpoints is array
        if (is_array($endpoint_array)) {
            $params = is_array($endpoint_array['params']) && !empty($endpoint_array['params']) ? implode($endpoint_array['params']) : '';

            $final_url = $endpoint_array['url'] . $params . $endpoint_array['token'];

            return $final_url;
        }
    }

    /**
     * Get Feed
     *
     * @uses CURL || file_get_contents || WP_Http
     *
     * @param $feeds_to_call (Array || string)
     * @return array
     * @since 1.0.0
     */
    function get_feed($feeds_to_call, $user_agent = '') {
        // data to be returned
        $response = array();
        $curl_success = true;
        if (is_callable('curl_init')) {
            if (is_array($feeds_to_call)) {
                // array of curl handles
                $curly = array();
                // multi handle
                $mh = curl_multi_init();
                // loop through $data and create curl handles then add them to the multi-handle
                foreach ($feeds_to_call as $id => $d) {
                    $curly[$id] = curl_init();
                    $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
                    curl_setopt($curly[$id], CURLOPT_URL, $url);
                    curl_setopt($curly[$id], CURLOPT_HEADER, 0);
                    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curly[$id], CURLOPT_SSL_VERIFYHOST, 0);
                    if (!empty($user_agent)) {
                        curl_setopt($curly[$id], CURLOPT_USERAGENT, $user_agent);
                    }

                    // post?
                    if (is_array($d)) {
                        if (!empty($d['post'])) {
                            curl_setopt($curly[$id], CURLOPT_POST, 1);
                            curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
                        }
                    }
                    // extra options?
                    if (!empty($options)) {
                        curl_setopt_array($curly[$id], $options);
                    }
                    curl_multi_add_handle($mh, $curly[$id]);
                }
                // execute the handles
                $running = null;
                do {
                    $curl_status = curl_multi_exec($mh, $running);
                    // Check for errors
                    $info = curl_multi_info_read($mh);
                    if (false !== $info) {
                        // Add connection info to info array:
                        if (!$info['result']) {
                            //$multi_info[(integer) $info['handle']]['error'] = 'OK';
                        } else {
                            $multi_info[(integer)$info['handle']]['error'] = curl_error($info['handle']);
                            $curl_success = false;
                        }
                    }
                } while ($running > 0);
                // get content and remove handles
                foreach ($curly as $id => $c) {
                    $response[$id] = curl_multi_getcontent($c);
                    curl_multi_remove_handle($mh, $c);
                }
                curl_multi_close($mh);
            }//END Is_ARRAY
            //NOT ARRAY SINGLE CURL
            else {
                $url = !is_array($feeds_to_call) && !empty($feeds_to_call) ? $feeds_to_call : '';
                $ch = curl_init($feeds_to_call);
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER => 0,
                    CURLOPT_POST => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0
                ));
                $response = curl_exec($ch);
                curl_close($ch);
            }

        }
        //File_Get_Contents if Curl doesn't work
        if (!$curl_success && ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE) {
            foreach ($feeds_to_call as $id => $d) {
                $response[$id] = @file_get_contents($d);
            }
        } else {
            //If nothing else use wordpress http API
            if (!$curl_success && !class_exists('WP_Http')) {
                include_once(ABSPATH . WPINC . '/class-http.php');
                $wp_http_class = new WP_Http;
                foreach ($feeds_to_call as $id => $d) {
                    $wp_http_result = $wp_http_class->request($d);
                    $response[$id] = $wp_http_result['body'];
                }
            }
            //Do nothing if Curl was Successful
        }
        return $response;
    }

    /**
     * Create feed cache
     *
     * @param $transient_name
     * @param $response
     * @return string
     * @since 1.0.0
     */
    function create_feed_cache($transient_name, $response) {
        $final_trans_name = $this->trans_prefix . $transient_name;
        $trans_lenth = strlen($final_trans_name);
        if ($trans_lenth <= 45) {
            set_transient($final_trans_name, $response, $this->trans_cache_time);
        } else {
            return 'Cache was not set because Transient Name was more then 45 characters including trans_prefix';
        }

    }

    /**
     * Get feed cache
     *
     * @param $transient_name
     * @return mixed
     * @since 1.0.0
     */
    function get_feed_cache($transient_name) {
        $returned_cache_data = get_transient($this->trans_prefix . $transient_name);
        return $returned_cache_data;
    }

    /**
     * Check if feed cache exists
     *
     * NOTE - If the transient does not exist, does not have a value, or has expired, then get_transient will return false.
     *
     * @param $transient_name
     * @return bool
     * @since 1.0.0
     */
    function check_feed_cache_exists($transient_name) {
        if (false === ($special_query_results = get_transient($this->trans_prefix . $transient_name))) {
            return false;
        }
        return true;
    }

    /**
     * Clear feed cache using Ajax
     *
     * @since 1.0.0
     */
    public function clear_cache_ajax() {
        global $wpdb;
        $not_expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_' . $this->trans_prefix . '%'));
        $expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_' . $this->trans_prefix . '%'));
        wp_reset_query();
        echo 'it worked';
        return;
    }

    /**
     * Clear cache (non-ajax)
     *
     * @return string
     * @since 1.0.0
     */
    function clear_cache() {
        global $wpdb;
        $not_expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_' . $this->trans_prefix . '%'));
        $expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_' . $this->trans_prefix . '%'));
        wp_reset_query();
        return 'Cache for all Feeds cleared!';
    }
}