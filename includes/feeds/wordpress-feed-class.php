<?php namespace Sidebar_Support;

class WordPress_Feed {

    public function get_plugins_by_author($author) {
        $display_list = new Display_List;
        $aff = new API_Feed_Fetch();
        //Check If feed is already cached.
        $cache = $aff->check_feed_cache_exists('wp_plugins_feed');
        if($cache === true){
            $plugins = $aff->get_feed_cache('wp_plugins_feed');
            $new_cache = false;
        }else{
            $plugins = $this->use_wp_remote_post('plugins', $author);
            $new_cache = true;
        }
        // Display a list of the plug-ins and the number of downloads
        if (is_array($plugins)) {
            //Check if Cache exists if not create it!
            $cache = $aff->check_feed_cache_exists('wp_plugins_feed');
            //Create Feed Cache
            if(!$cache){
                if(isset($plugins) && is_array($plugins) && !empty($plugins)){
                    $aff->create_feed_cache('wp_plugins_feed', $plugins);
                }
            }
            //Build Links/Buttons
            foreach ($plugins as $plugin) {
                $item_array[] = array(
                    'name' => esc_html($plugin->name),
                    'main_url' => 'https://wordpress.org/plugins/' . $plugin->slug . '/',
                    'buttons' => array(
                        'Support' => 'https://wordpress.org/support/plugin/' . $plugin->slug . '/',
                        'Changelog' => 'https://wordpress.org/support/plugin/' . $plugin->slug . '/changelog/',
                        'Reviews (' . $plugin->num_ratings . ')' => 'https://wordpress.org/support/view/plugin-reviews/' . $plugin->slug . '/',
                        'Download' => 'https://downloads.wordpress.org/plugin/' . $plugin->slug . '.latest-stable.zip',
                    ),
                );
            }
            echo $display_list->display_feed_list('WordPress_plugins', $item_array);
        } elseif (empty($plugins)) {
            echo 'There are no plugins for this Author or this Author does not exist.';
        } else {
            //Display Error Message returned by use_wp_remote_post.
            echo $plugins;
        }
    }
    public function get_themes_by_author($author) {
        $display_list = new Display_List;
        $aff = new API_Feed_Fetch();
        //Check If feed is already cached.
        $cache = $aff->check_feed_cache_exists('wp_themes_feed');
        if($cache === true){
            $themes = $aff->get_feed_cache('wp_themes_feed');
            $new_cache = false;
        }else{
            $themes = $this->use_wp_remote_post('themes', $author);
            $new_cache = true;
        }
        $themes = $this->use_wp_remote_post('themes', $author);
        // Display a list of the plug-ins and the number of downloads
        if (is_array($themes)) {
            //Check if Cache exists if not create it!
            $cache = $aff->check_feed_cache_exists('wp_themes_feed');
            //Create Feed Cache
            if(!$cache && $new_cache){
                if(isset($themes) && is_array($themes) && !empty($themes) ){
                    $aff->create_feed_cache('wp_themes_feed', $themes);
                }
            }
            //Build Links/Buttons
            foreach ($themes as $theme) {
                $item_array[] = array(
                    'name' => esc_html($theme->name),
                    'main_url' => 'https://wordpress.org/themes/' . $theme->slug . '/',
                    'buttons' => array(
                        'Support' => 'https://wordpress.org/support/theme/' . $theme->slug . '/',
                        'Reviews (' . $theme->num_ratings . ')' => 'https://wordpress.org/support/view/theme-reviews/' . $theme->slug . '/',
                        'Download' => 'https://downloads.wordpress.org/theme/' . $theme->slug . '.latest-stable.zip',
                    ),
                );
            }
            echo $display_list->display_feed_list('WordPress_themes', $item_array);
        }elseif (empty($themes)) {
            echo 'There are no Themes for this Author or this Author does not exist.';
        } else {
            //Display Error Message returned by use_wp_remote_post.
            echo $themes;
        }
    }

    public function use_wp_remote_post($type, $author) {
        $args = array(
            'author' => $author
        );
        switch ($type) {
            case 'plugins':
                $action = 'query_plugins';
                break;
            case 'themes':
                $action = 'query_themes';
                break;
        }
        // Make request and extract objects.
        $response = wp_remote_post(
            'http://api.wordpress.org/plugins/info/1.0/',
            array(
                'body' => array(
                    'action' => $action,
                    'request' => serialize((object)$args)
                )
            )
        );
        if (!is_wp_error($response)) {
            $returned_object = unserialize(wp_remote_retrieve_body($response));
            $returned_objects = $returned_object->plugins;
            if (!is_array($returned_objects)) {
                //Response body does not contain an object/array
                return 'No ' . $type . ' found.';
            } else {
                //It Worked. Return Objects
                return $returned_objects;
            }
        } else {
            // Error object returned
            return 'An error has occurred';
        }
    }

}