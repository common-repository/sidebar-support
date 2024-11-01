<?php
namespace Sidebar_Support;
class Settings_Page
{
    function __construct()
    {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'add_settings_page_scripts_styles'));
        }
    }

    /**
     * Add Settings Page Scripts Styles
     *
     * @since 1.9.6
     */
    public function add_settings_page_scripts_styles()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'side-sup-settings-page') {
            wp_register_style('font-awesome', plugins_url('icon-picker/dist/css/font-awesome.min.css', __FILE__));
            wp_enqueue_style('font-awesome');
            //Settings Page CSS
            wp_register_style('side_sup_settings_css', plugins_url('css/admin-pages.css', __FILE__));
            wp_enqueue_style('side_sup_settings_css');
        }

    }

    /**
     * Sidebar Support Settings Page
     *
     * @since 1.0.0
     */
    function Settings_Page()
    {
        //Sidebar Support Functions Class
        $feeds_core = new API_Feed_Fetch();
        ?>
        <div class="side-sup-main-template-wrapper-all">

            <div class="side-sup-settings-admin-wrap" id="theme-settings-wrap">
                <h2><?php _e('Sidebar Support Settings', 'sidebar-support'); ?></h2>
                <a class="buy-extensions-btn" href="http://www.slickremix.com/sidebar-support-documentation/" target="_blank"><?php _e('Setup Documentation', 'sidebar-support'); ?></a>

                <div class="side-sup-settings-admin-input-wrap company-info-style side-sup-cache-wrap" style="padding-bottom: 0px;">

                    <div class="side-sup-settings-admin-input-label"><?php _e('Clear Cache Options for Wordpress, Github or Bitbucket Feeds.', 'sidebar-support'); ?></div>
                    <div class="pages-selections-wrap">
                        <div class="side-sup-clear-cache">
                            <div class="use-of-plugin"><?php _e('Please Clear Cache if you want to get the latest info from your Feeds.', 'feed-them-social'); ?></div>
                            <?php if (isset($_GET['cache']) && $_GET['cache'] == 'clearcache') {
                                echo '<div class="side-sup-clear-cache-text">' . $feeds_core->clear_cache() . '</div>';
                            }
                            isset($ssDevModeCache) ? $ssDevModeCache : "";
                            isset($ssAdminBarMenu) ? $ssAdminBarMenu : "";
                            $ssAdminBarMenu = get_option('side-sup-admin-bar-menu');
                            $ssCacheTime = get_option('side-sup-cache-time');
                            ?>

                            <form method="post" action="edit.php?post_type=ss_quick_responses&page=side-sup-settings-page&cache=clearcache">
                                <input class="side-sup-admin-submit-btn" type="submit" value="<?php _e('Clear All Feeds Cache', 'feed-them-social'); ?>"/>
                            </form>
                        </div><!--/feed-them-clear-cache-->
                    </div><!--/.pages-selections-wrap-->
                    <div class="clear"></div>
                </div>
                <!--/side-sup-settings-admin-input-wrap-->

                <form method="post" class="side-sup-settings-admin-form" action="options.php">
                    <?php // get our registered settings from the gq theme functions
                    settings_fields('side-sup-settings'); ?>
                    <div class="side-sup-settings-admin-input-wrap company-info-style side-sup-cache-wrap-bottom">
                        <div class="pages-selections-wrap">
                            <label><?php _e('Cache Time', 'feed-them-social'); ?></label>
                            <select id="side-sup-cache-time" name="side-sup-cache-time">
                                <option value="10" <?php if ($ssCacheTime == '10') echo 'selected="selected"'; ?>><?php _e('10 Minutes', 'feed-them-social'); ?></option>
                                <option value="30" <?php if ($ssCacheTime == '30') echo 'selected="selected"'; ?>><?php _e('30 Minutes', 'feed-them-social'); ?></option>
                                <option value="60" <?php if ($ssCacheTime == '60') echo 'selected="selected"'; ?>><?php _e('60 Minutes', 'feed-them-social'); ?></option>
                                <option value="0" <?php if ($ssCacheTime == '0') echo 'selected="selected"'; ?>><?php _e('Clear cache on every page load', 'feed-them-social'); ?></option>
                            </select>
                            <div class="clear"></div>
                            <label><?php _e('Menu Bar', 'feed-them-social'); ?></label>
                            <select id="side-sup-admin-bar-menu" name="side-sup-admin-bar-menu">
                                <option value="show-admin-bar-menu" <?php if ($ssAdminBarMenu == 'show-admin-bar-menu') echo 'selected="selected"'; ?>><?php _e('Show Admin Bar Menu', 'feed-them-social'); ?></option>
                                <option value="hide-admin-bar-menu" <?php if ($ssAdminBarMenu == 'hide-admin-bar-menu') echo 'selected="selected"'; ?>><?php _e('Hide Admin Bar Menu', 'feed-them-social'); ?></option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="side-sup-settings-admin-input-wrap company-info-style">

                        <div class="side-sup-settings-admin-input-label"><?php _e('Show Menu on Pages, Posts, Custom Post Types etc.', 'sidebar-support'); ?></div>
                        <div class="pages-selections-wrap">
                            <p class="special">
                                <input name="side-sup-page-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-page-options" value="1" <?php echo checked('1', get_option('side-sup-page-options')); ?>/>
                                <?php _e('Pages', 'sidebar-support'); ?></p>
                            <p class="special">
                                <input name="side-sup-post-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-post-options" value="1" <?php echo checked('1', get_option('side-sup-post-options')); ?>/>
                                <?php _e('Posts', 'sidebar-support'); ?></p>
                            <p class="special">
                                <input name="side-sup-home-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-home-options" value="1" <?php echo checked('1', get_option('side-sup-home-options')); ?>/>
                                <?php _e('Home', 'sidebar-support'); ?></p>
                            <p class="special">
                                <input name="side-sup-category-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-category-options" value="1" <?php echo checked('1', get_option('side-sup-category-options')); ?>/>
                                <?php _e('Categories', 'sidebar-support'); ?></p>
                            <p class="special">
                                <input name="side-sup-archive-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-archive-options" value="1" <?php echo checked('1', get_option('side-sup-archive-options')); ?>/>
                                <?php _e('Archives', 'sidebar-support'); ?></p>
                            <p class="special">
                                <input name="side-sup-tags-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-tags-options" value="1" <?php echo checked('1', get_option('side-sup-tags-options')); ?>/>
                                <?php _e('Tags', 'sidebar-support'); ?></p>
                            <p class="special">
                                <input name="side-sup-errorpage-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-errorpage-options" value="1" <?php echo checked('1', get_option('side-sup-errorpage-options')); ?>/>
                                <?php _e('404', 'sidebar-support'); ?></p>
                            <p class="special">
                                <input name="side-sup-search-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-search-options" value="1" <?php echo checked('1', get_option('side-sup-search-options')); ?>/>
                                <?php _e('Search', 'sidebar-support'); ?></p>
                            <?php
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
                                ?><p class="special">
                                <input name="<?php echo $final_post_type_name ?>" class="side-sup-settings-admin-input" type="checkbox"  id="<?php echo $final_post_type_name ?>" value="1" <?php echo checked('1', get_option($final_post_type_name)); ?> /><?php echo $post_type ?>
                                </p> <?php
                            } ?> </div><!--/.pages-selections-wrap-->
                        <div class="clear"></div>
                    </div>
                    <!--/side-sup-settings-admin-input-wrap-->

                    <div class="side-sup-settings-admin-input-wrap company-info-style side-sup-turn-on-custom-colors">
                        <div class="side-sup-settings-admin-input-label"><?php _e('Check the box to turn ON the custom options.', 'sidebar-support'); ?></div>
                        <p class="special">
                            <input name="side-sup-options-settings-custom-css-options" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-options-settings-custom-css-options" value="1" <?php echo checked('1', get_option('side-sup-options-settings-custom-css-options')); ?>/>
                            <?php
                            if (get_option('side-sup-options-settings-custom-css-options') == '1') {
                                _e('<strong>Checked:</strong> Custom style options being used now.', 'sidebar-support');
                            } else {
                                _e('<strong>Not Checked:</strong> You are using the default styles.', 'sidebar-support');
                            }
                            ?>
                        </p>
                        <p>
                            <label><?php _e('Feed Width:', 'sidebar-support'); ?></label>
                            <input name="side-sup-main-wrapper-width-input" type="text" id="side-sup-main-wrapper-width-input" placeholder="450px <?php _e('or', 'sidebar-support'); ?> 100% <?php _e('to fill screen', 'sidebar-support'); ?>" value="<?php echo get_option('side-sup-main-wrapper-width-input'); ?>" title="Only Numbers and px are allowed"/>
                        </p>
                        <div class="clear"></div>
                        <p>
                            <label><?php _e('Bar Position:', 'sidebar-support'); ?></label>
                        <div class="side-sup-custom-settings-checkbox-wrap">
                            <input name="side-sup-bottom-position" type="checkbox" id="side-sup-bottom-position" value="1" <?php echo checked('1', get_option('side-sup-bottom-position')); ?>/>
                            <?php
                            if (get_option('side-sup-bottom-position') == '1') {
                                _e('Sidebar Support Menu in the bottom left corner', 'sidebar-support');
                            } else {
                                _e('Show Sidebar Support Menu in the bottom left corner', 'sidebar-support');
                            }
                            ?>
                        </div>
                        </p>
                        <div class="clear"></div>
                        <p>
                            <label><?php _e('Mobile:', 'sidebar-support'); ?></label>
                        <div class="side-sup-custom-settings-checkbox-wrap">
                            <input name="side-sup-options-mobile" type="checkbox" id="side-sup-options-mobile" value="1" <?php echo checked('1', get_option('side-sup-options-mobile')); ?>/>
                            <?php
                            if (get_option('side-sup-options-mobile') == '1') {
                                _e('Sidebar Support Menu is NOT visible on mobile', 'sidebar-support');
                            } else {
                                _e('Sidebar Support Menu is visible on mobile', 'sidebar-support');
                            }
                            ?>
                        </div>
                        </p>
                        <div class="clear"></div>
                        <p>
                            <label><?php _e('Tablet:', 'sidebar-support'); ?></label>
                        <div class="side-sup-custom-settings-checkbox-wrap">
                            <input name="side-sup-options-tablets" type="checkbox" id="side-sup-options-tablets" value="1" <?php echo checked('1', get_option('side-sup-options-tablets')); ?>/>
                            <?php
                            if (get_option('side-sup-options-tablets') == '1') {
                                _e('The Sidebar Support Menu is NOT visible on tablets', 'sidebar-support');
                            } else {
                                _e('The Sidebar Support Menu is visible on tablets', 'sidebar-support');
                            }
                            ?>
                        </div>
                        </p>
                        <div class="clear"></div>
                        <br/> <br/>
                        <div class="side-sup-settings-admin-input-label"><?php _e('Check box to turn ON custom CSS options.', 'sidebar-support'); ?></div>
                        <p class="special">
                            <input name="side-sup-options-settings-custom-css-second" type="checkbox" id="side-sup-options-settings-custom-css-second" value="1" <?php echo checked('1', get_option('side-sup-options-settings-custom-css-second')); ?>/>
                            <?php
                            if (get_option('side-sup-options-settings-custom-css-second') == '1') {
                                _e('<strong>Checked:</strong> Custom CSS option is being used now.', 'sidebar-support');
                            } else {
                                _e('<strong>Not Checked:</strong> You are using the default CSS.', 'sidebar-support');
                            }
                            ?>
                        </p>
                        <p>
                            <label class="toggle-custom-textarea-show"><span><?php _e('Show', 'sidebar-support'); ?></span><span class="toggle-custom-textarea-hide"><?php _e('Hide', 'sidebar-support'); ?></span> <?php _e('custom CSS', 'sidebar-support'); ?>
                            </label>
                        <div class="side-sup-custom-css-text"><?php _e('<p>Add Your Custom CSS Code below.</p>', 'sidebar-support'); ?></div>
                        <textarea name="side-sup-settings-admin-textarea-css" class="side-sup-settings-admin-textarea-css" id="side-sup-main-wrapper-css-input"><?php echo get_option('side-sup-settings-admin-textarea-css'); ?></textarea>
                        </p>
                        <div class="clear"></div>
                    </div>
                    <!--/side-sup-settings-admin-input-wrap-->
                    <h3><?php _e('Custom Color Options', '	'); ?></h3>
                    <div class="side-sup-settings-admin-input-wrap company-info-style side-sup-turn-on-custom-colors">
                        <div class="view-all-custom">
                            <a class="icon-view-all side-sup-color-options-open-close-all" href="#"><span class="view-all-articles"><?php _e('open / close', 'sidebar-support'); ?>
                                    <br>
                                    <?php _e('help photos', 'sidebar-support'); ?>
                                    <span class="arrow-right"></span></span></a></div>
                        <div class="side-sup-settings-admin-input-label side-sup-wp-header-custom"><?php _e('Check box to turn on custom color options for Sidebar Support.', 'sidebar-support'); ?></div>
                        <p>
                            <input name="side-sup-settings-custom-css" class="side-sup-settings-admin-input" type="checkbox" id="side-sup-settings-custom-css" value="1" <?php echo checked('1', get_option('side-sup-settings-custom-css')); ?>/>
                            <?php
                            if (get_option('side-sup-settings-custom-css') == '1') {
                                _e('<strong>Checked:</strong> Custom styles being used now.', 'sidebar-support');
                            } else {
                                _e('<strong>Not Checked:</strong> You are using the default theme colors.', 'sidebar-support');
                            }
                            ?>
                        </p>
                        <a id="default-values-side-sup-pro-option1" class="default-values-side-sup-pro-option1 side-sup-custom-color-btn" href="javascript:;"><?php _e('Set Default Icon Colors', 'sidebar-support'); ?></a>
                        <a class="default-values-side-sup-pro-option2 side-sup-custom-color-btn" href="javascript:;"><?php _e('Set Icon Color Option 2', 'sidebar-support'); ?></a>
                        <div class="clear"></div>
                        <div id="side-sup-social-bar-icons-wrap">
                            <?php
                            $side_sup_quick_resp_icon = get_option('side-sup-quick-resp-icon') ? get_option('side-sup-quick-resp-icon') : 'fa-support';
                            $side_sup_quick_link_icon = get_option('side-sup-quick-link-icon') ? get_option('side-sup-quick-link-icon') : 'fa-link';

                            if (get_option('side-sup-menu1')) { ?>
                                <div id="open-quick-response" class="fgcolor fa-fw <?php echo $side_sup_quick_resp_icon ?>"></div>
                            <?php }
                            if (get_option('side-sup-menu2')) { ?>
                                <div id="open-quick-links" class="bgcolor fa-fw <?php echo $side_sup_quick_link_icon ?>"></div>
                            <?php }
                            if (get_option('side-sup-menu3')) { ?>
                                <div id="open-wordpress-org" class="fa fa-fw fa-wordpress"></div>
                            <?php }
                            if (get_option('side-sup-menu4')) { ?>
                                <div id="open-github" class="fa fa-fw fa-github"></div>
                            <?php }
                            if (get_option('side-sup-menu5')) { ?>
                                <div id="open-bitbucket" class="fa fa-fw fa-bitbucket"></div>
                            <?php }
                            if (get_option('side-sup-menu6')) { ?>
                                <div id="open-gitlab" class="fa fa-fw fa-gitlab"></div>
                            <?php } ?>
                        </div>
                        <!-- Styles here so we can show the new colors chosen for the icons. -->
                        <style>
                            #side-sup-social-bar-icons-wrap #open-quick-response {
                                color: <?php echo get_option('side-sup-quick-response-icon-color'); ?>;
                                background: <?php echo get_option('side-sup-quick-response-background-color'); ?>;
                            }

                            #side-sup-social-bar-icons-wrap #open-quick-links {
                                color: <?php echo get_option('side-sup-quick-links-icon-color'); ?>;
                                background: <?php echo get_option('side-sup-quick-links-background-color'); ?>;
                            }

                            #side-sup-social-bar-icons-wrap #open-github {
                                color: <?php echo get_option('side-sup-github-icon-color'); ?>;
                                background: <?php echo get_option('side-sup-github-background-color'); ?>;
                            }

                            #side-sup-social-bar-icons-wrap #open-wordpress-org {
                                color: <?php echo get_option('side-sup-wordpress-icon-color'); ?>;
                                background: <?php echo get_option('side-sup-wordpress-background-color'); ?>;
                            }

                            #side-sup-social-bar-icons-wrap #open-gitlab {
                                color: <?php echo get_option('side-sup-gitlab-icon-color'); ?>;
                                background: <?php echo get_option('side-sup-gitlab-background-color'); ?>;
                            }

                            #side-sup-social-bar-icons-wrap #open-bitbucket {
                                color: <?php echo get_option('side-sup-bitbucket-icon-color'); ?>;
                                background: <?php echo get_option('side-sup-bitbucket-background-color'); ?>;
                            }
                        </style>

                    </div>
                    <!--/side-sup-settings-admin-input-wrap-->

                    <div class="side-sup-float-wrap-2column">

                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Quick Responses Icon', 'sidebar-support'); ?></div>
                            <input name="side-sup-quick-response-icon-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-quick-response-icon-color', onFineChange:'setQuickResponseIconColor(this)', backgroundColor: '#FFFFFF', padding:0, borderWidth:0}" type="text" id="side-sup-quick-response-icon-color" value="<?php echo get_option('side-sup-quick-response-icon-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                                This will change the color of the icon itself.
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Quick Responses Icon Background', 'sidebar-support'); ?></div>
                            <input name="side-sup-quick-response-background-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-quick-response-background-color', onFineChange:'setQuickResponseIconBackgColor(this)', backgroundColor: '#757575', padding:0, borderWidth:0}" type="text" id="side-sup-quick-response-background-color" value="<?php echo get_option('side-sup-quick-response-background-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Quick Links Icon', 'sidebar-support'); ?></div>
                            <input name="side-sup-quick-links-icon-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-quick-links-icon-color', onFineChange:'setQuickLinksIconColor(this)', backgroundColor: '#FFFFFF', padding:0, borderWidth:0}" type="text" id="side-sup-quick-links-icon-color" value="<?php echo get_option('side-sup-quick-links-icon-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Quick Links Icon Background', 'sidebar-support'); ?></div>
                            <input name="side-sup-quick-links-background-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-quick-links-background-color', onFineChange:'setQuickLinksIconBackgColor(this)', backgroundColor: '#757575', padding:0, borderWidth:0}" type="text" id="side-sup-quick-links-background-color" value="<?php echo get_option('side-sup-quick-links-background-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Wordpress Icon', 'sidebar-support'); ?></div>
                            <input name="side-sup-wordpress-icon-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-wordpress-icon-color', onFineChange:'setWordpressIconColor(this)', backgroundColor: '#FFFFFF', padding:0, borderWidth:0}" type="text" id="side-sup-wordpress-icon-color" value="<?php echo get_option('side-sup-wordpress-icon-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Wordpress Icon Background', 'sidebar-support'); ?></div>
                            <input name="side-sup-wordpress-background-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-wordpress-background-color', onFineChange:'setWordpressIconBackgColor(this)', backgroundColor: '#757575', padding:0, borderWidth:0}" type="text" id="side-sup-wordpress-background-color" value="<?php echo get_option('side-sup-wordpress-background-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Github Icon', 'sidebar-support'); ?></div>
                            <input name="side-sup-github-icon-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-github-icon-color', onFineChange:'setGithubIconColor(this)', backgroundColor: '#FFFFFF', padding:0, borderWidth:0}" type="text" id="side-sup-github-icon-color" value="<?php echo get_option('side-sup-github-icon-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Github Icon Background', 'sidebar-support'); ?></div>
                            <input name="side-sup-github-background-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-github-background-color', onFineChange:'setGithubIconBackgColor(this)', backgroundColor: '#757575', padding:0, borderWidth:0}" type="text" id="side-sup-github-background-color" value="<?php echo get_option('side-sup-github-background-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>

                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Bitbucket Icon', 'sidebar-support'); ?></div>
                            <input name="side-sup-bitbucket-icon-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-bitbucket-icon-color', onFineChange:'setBitbucketIconColor(this)', backgroundColor: '#FFFFFF', padding:0, borderWidth:0}" type="text" id="side-sup-bitbucket-icon-color" value="<?php echo get_option('side-sup-bitbucket-icon-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Bitbucket Icon Background', 'sidebar-support'); ?></div>

                            <input name="side-sup-bitbucket-background-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-bitbucket-background-color', onFineChange:'setBitbucketIconBackgColor(this)', backgroundColor: '#757575', padding:0, borderWidth:0}" type="text" id="side-sup-bitbucket-background-color" value="<?php echo get_option('side-sup-bitbucket-background-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <div class="side-sup-settings-admin-input-wrap company-info-style" style="display: none;">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Gitlab Icon', 'sidebar-support'); ?></div>
                            <input name="side-sup-gitlab-icon-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-gitlab-icon-color', onFineChange:'setGitlabIconColor(this)', backgroundColor: '#FFFFFF', padding:0, borderWidth:0}" type="text" id="side-sup-gitlab-icon-color" value="<?php echo get_option('side-sup-gitlab-icon-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style" style="display: none;">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Gitlab Icon Background', 'sidebar-support'); ?></div>
                            <input name="side-sup-gitlab-background-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-gitlab-background-color', onFineChange:'setGitlabIconBackgColor(this)', backgroundColor: '#757575', padding:0, borderWidth:0}" type="text" id="side-sup-gitlab-background-color" value="<?php echo get_option('side-sup-gitlab-background-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Header #1', 'sidebar-support'); ?></div>
                            <input name="side-sup-h1-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-h1-color', backgroundColor: '#1D1F24', padding:0, borderWidth:0}" type="h1" id="side-sup-h1-color" value="<?php echo get_option('side-sup-h1-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Header #2', 'sidebar-support'); ?></div>
                            <input name="side-sup-h2-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-h2-color', backgroundColor: '#4F4F4F', padding:0, borderWidth:0}" type="h2" id="side-sup-h2-color" value="<?php echo get_option('side-sup-h2-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Header #3', 'sidebar-support'); ?></div>
                            <input name="side-sup-h3-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-h3-color', backgroundColor: '#969696', padding:0, borderWidth:0}" type="h3" id="side-sup-h3-color" value="<?php echo get_option('side-sup-h3-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Text', 'sidebar-support'); ?></div>
                            <input name="side-sup-text-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-text-color', backgroundColor: '#A8A8A8', padding:0, borderWidth:0}" type="text" id="side-sup-text-color" value="<?php echo get_option('side-sup-text-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Button Background (WP, Github and Bitbucket)', 'sidebar-support'); ?></div>
                            <input name="side-sup-button-background-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-button-background-color', backgroundColor: '#EBEBEB', padding:0, borderWidth:0}" type="button-background" id="side-sup-button-background-color" value="<?php echo get_option('side-sup-button-background-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Panels Background', 'sidebar-support'); ?></div>
                            <input name="side-sup-panels-background" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-panels-background', backgroundColor: '#FFFFFF', padding:0, borderWidth:0}" type="text" id="side-sup-panels-background" value="<?php echo get_option('side-sup-panels-background'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Close Button', 'sidebar-support'); ?></div>
                            <input name="side-sup-close-button" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-close-button', backgroundColor: '#292929', padding:0, borderWidth:0}" type="text" id="side-sup-close-button" value="<?php echo get_option('side-sup-close-button'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Border Bottom Color', 'sidebar-support'); ?></div>
                            <input name="side-sup-border-bottom-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-border-bottom-color', backgroundColor: '#B3B3B3', padding:0, borderWidth:0}" type="text" id="side-sup-border-bottom-color" value="<?php echo get_option('side-sup-border-bottom-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">

                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->

                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('All Links', 'sidebar-support'); ?></div>
                            <input name="side-sup-link-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-link-color', backgroundColor: '#424242', padding:0, borderWidth:0}" type="text" id="side-sup-link-color" value="<?php echo get_option('side-sup-link-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="side-sup-settings-admin-input-wrap company-info-style">
                            <div class="side-sup-settings-admin-input-label"><?php _e('Links Hover', 'sidebar-support'); ?></div>
                            <input name="side-sup-link-hover-color" class="side-sup-settings-admin-input jscolor {hash:true,valueElement:'side-sup-link-hover-color', backgroundColor: '#595959', padding:0, borderWidth:0}" type="text" id="side-sup-link-hover-color" value="<?php echo get_option('side-sup-link-hover-color'); ?>"/>
                            <div class="side-sup-settings-admin-input-example"></div>
                            <div class="clear"></div>
                            <a class="side-sup-settings-toggle" href="#"></a>
                            <div class="side-sup-settings-id-answer">
                            </div>
                            <!--/side-sup-settings-id-answer-->
                            <div class="clear"></div>
                        </div>
                        <!--/side-sup-settings-admin-input-wrap-->
                        <div class="clear"></div>
                    </div>
                    <!-- side-sup-float-wrap-2column -->
                    <input type="submit" class="side-sup-settings-admin-submit-btn" value="<?php _e('Save All Changes', 'sidebar-support') ?>"/>
                    <!-- <input class="side-sup-settings-admin-submit-btn" name="Reset" type="submit" value="< ?php _e('reset', side-sup-settings-title-color); ?>" />
                         <input name="action" type="hidden" value="reset" /> -->
                </form>
                <div class="side-sup-settings-icon-wrap">
                    <a href="https://www.facebook.com/SlickRemix" target="_blank" class="facebook-icon"></a></div>
                <a class="side-sup-settings-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
            </div>
            <!--/side-sup-settings-admin-wrap-->
            <div class="clear"></div>
        </div>
        <!--/side-sup-main-template-wrapper-all-->

        <h1 class="plugin-author-note"><?php _e('Plugin Authors Note', 'sidebar-support') ?></h1>
        <div class="fts-plugin-reviews">
            <div class="fts-plugin-reviews-rate">Sidebar Support was created by 2 Brothers, Spencer and Justin Labadie.
                That’s it, 2 people! We spend all our time creating and supporting this plugin. Show us some love if you
                like our plugin and leave a quick review for us, it will make our day!
                <a href="https://wordpress.org/plugin-reviews/sidebar-support" target="_blank">Leave us a
                    Review ★★★★★</a>
            </div>
            <div class="fts-plugin-reviews-support">If you're having troubles getting setup please contact us. We will
                respond within 24hrs, but usually within 1-6hrs.
                <a href="http://www.slickremix.com/support-forum/forum/" target="_blank">Support Forum</a>
                <div class="fts-text-align-center">
                    <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
                </div>
            </div>
        </div>

        <!-- These scripts must load in the footer of page -->
        <script type="text/javascript" src="<?php echo plugins_url(); ?>/sidebar-support/admin/js/jscolor/jscolor.js"></script>
        <?php
    }
}//END Class