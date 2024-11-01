<?php
namespace Sidebar_Support;
class sidebar_support_settings_page {
	function __construct() {
	}
	//**************************************************
	// FTS Bar Settings Page
	//**************************************************
	function sidebar_support_settings_page() {
		//FTS Functions Class
		$sidebar_support_functions = new sidebar_support_functions();
	?>
		<div class="sidebar-support-main-template-wrapper-all">
		<div class="sidebar-support-settings-admin-wrap" id="theme-settings-wrap">
		  <h2><?php _e('FTS Bar Settings', 'sidebar-support'); ?></h2>
		  <a class="buy-extensions-btn" href="http://www.slickremix.com/docs/sidebar-support/" target="_blank"><?php _e('Setup Documentation', 'sidebar-support'); ?></a>
		  <form method="post" class="sidebar-support-settings-admin-form" action="options.php">
		    <?php // get our registered settings from the gq theme functions
				  settings_fields('sidebar-support-settings'); ?>
		    <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		      <div class="sidebar-support-settings-admin-input-label"><?php _e("Choose the social media feeds you would like visible", 'sidebar-support'); ?></div>
		      <p class="special">
		        <input name="sidebar-support-quick-response-feed" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-quick-response-feed" value="1" <?php echo checked( '1', get_option( 'sidebar-support-quick-response-feed' ) ); ?>/>
		        <?php
		if (get_option( 'sidebar-support-quick-response-feed' ) == '1') {
		   _e('Quick Responses ON', 'sidebar-support');
		}
		else	{
		  _e('Quick Responses OFF', 'sidebar-support');
		}
			?><a href="javascript:;" id="fb-bar-options-quick-response"><?php _e('Options', 'sidebar-support'); ?></a></p>
		      <div class="sidebar-support-quick-response-options-wrap">
		    <div class="sidebar-support-fb-options">
		    <div class="sidebar-support-settings-admin-input-label"><?php _e("Which type of Quick Responses Feed would you like to use for Quick Responses icon?", 'sidebar-support'); ?></div>
			<input type="radio" name="sidebar-support-quick-response-feed-type" class="sidebar-support-settings-admin-input fb-option-trigger" data-rel="sidebar-support-fb-page-option-wrap" value="quick-response_page" <?php echo checked( 'quick-response_page', get_option( 'sidebar-support-quick-response-feed-type' ) ); ?>/><?php _e("Quick Responses Page Feed", 'sidebar-support'); ?><br>
			<input type="radio" name="sidebar-support-quick-response-feed-type" class="sidebar-support-settings-admin-input fb-option-trigger" data-rel="sidebar-support-fb-group-option-wrap" value="quick-response_group" <?php echo checked( 'quick-response_group', get_option( 'sidebar-support-quick-response-feed-type' ) ); ?>/><?php _e("Quick Responses Group Feed", 'sidebar-support'); ?><br>
			<input type="radio" name="sidebar-support-quick-response-feed-type" class="sidebar-support-settings-admin-input fb-option-trigger" data-rel="sidebar-support-fb-event-option-wrap" value="quick-response_event" <?php echo checked( 'quick-response_event', get_option( 'sidebar-support-quick-response-feed-type' ) ); ?>/><?php _e("Quick Responses Event Feed", 'sidebar-support'); ?><br>
		    </div>
		    <div class="sidebar-support-fb-page-option-wrap sidebar-support-fb-options-toggle <?php if (get_option( 'sidebar-support-quick-response-feed-type' ) == 1) {?>hide-fb-options<?php } ?>">
		     <?php
		         //Add Quick Responses Page Form
		    //     echo $sidebar_support_functions->sidebar-support_quick-response_page_form(true);  ?>
		      </div>
		     <div class="sidebar-support-fb-group-option-wrap sidebar-support-fb-options-toggle hide-fb-options <?php if (get_option( 'sidebar-support-quick-response-feed-type' ) == 1) {?>hide-fb-options<?php } ?>">
		     <?php
		         //Add Quick Responses Event Form
		     //    echo $sidebar_support_functions->sidebar-support_quick-response_group_form(true);  ?>
		    </div>
		    <div class="sidebar-support-fb-event-option-wrap sidebar-support-fb-options-toggle hide-fb-options <?php if (get_option( 'sidebar-support-quick-response-feed-type' ) == 1) {?>hide-fb-options<?php } ?>">
		     <?php
		         //Add Quick Responses Event Form
		    //     echo $sidebar_support_functions->sidebar-support_quick-response_event_form(true);
		       ?>
		     </div>
		 </div><!--/.quick-response-options-wrap-->
		      <div class="clear"></div>
		       <p class="special">
		        <input name="sidebar-support-quick-links-feed" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-quick-links-feed" value="1" <?php echo checked( '1', get_option( 'sidebar-support-quick-links-feed' ) ); ?>/>
		        <?php
		if (get_option( 'sidebar-support-quick-links-feed' ) == '1') {
		   _e('Quick Links ON', 'sidebar-support');
		}
		else	{
		  _e('Quick Links OFF', 'sidebar-support');
		}
		?><a href="javascript:;" id="fb-bar-options-quick-links"><?php _e('Options', 'sidebar-support'); ?></a></p>
		<div class="sidebar-support-quick-links-options-wrap">
			<?php
			//Add Quick Links Form
			// echo $sidebar_support_functions->sidebar-support_quick-links_form(true);
		   ?>
		   </div>
		      <div class="clear"></div>
		       <p class="special">
		        <input name="sidebar-support-github-feed" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-github-feed" value="1" <?php echo checked( '1', get_option( 'sidebar-support-github-feed' ) ); ?>/>
		        <?php
		if (get_option( 'sidebar-support-github-feed' ) == '1') {
		   _e('Github ON', 'sidebar-support');
		}
		else	{
		  _e('Github OFF', 'sidebar-support');
		}
			?><a href="javascript:;" id="fb-bar-options-github"><?php _e('Options', 'sidebar-support'); ?></a></p>
		    <div class="sidebar-support-github-options-wrap">
			<?php
			//Add Github Form
		//	 echo $sidebar_support_functions->sidebar-support_github_form(true);
		   ?>
		   </div>
		     <br/>
		      <div class="clear"></div>
		      <div class="sidebar-support-settings-admin-input-label"><?php _e('Show Feeds on Pages, Posts, Custom Post Types etc.', 'sidebar-support'); ?></div>
		   <div class="pages-selections-wrap">
		      <p class="special"><input name="sidebar-support-page-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-page-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-page-options' ) ); ?>/>
		        <?php  _e('Pages', 'sidebar-support'); ?></p>
		      <p class="special"><input name="sidebar-support-post-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-post-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-post-options' ) ); ?>/>
		        <?php  _e('Posts', 'sidebar-support'); ?></p>
		      <p class="special"><input name="sidebar-support-home-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-home-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-home-options' ) ); ?>/>
		        <?php  _e('Home', 'sidebar-support'); ?></p>
		      <p class="special"><input name="sidebar-support-category-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-category-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-category-options' ) ); ?>/>
		        <?php  _e('Categories', 'sidebar-support'); ?></p>
		         <p class="special"><input name="sidebar-support-archive-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-archive-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-archive-options' ) ); ?>/>
		        <?php  _e('Archives', 'sidebar-support'); ?></p>
		         <p class="special"><input name="sidebar-support-tags-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-tags-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-tags-options' ) ); ?>/>
		        <?php  _e('Tags', 'sidebar-support'); ?></p>
		         <p class="special"><input name="sidebar-support-errorpage-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-errorpage-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-errorpage-options' ) ); ?>/>
		        <?php  _e('404', 'sidebar-support'); ?></p>
		        <p class="special"><input name="sidebar-support-search-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-search-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-search-options' ) ); ?>/>
		        <?php  _e('Search', 'sidebar-support'); ?></p>
		      <?php
		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);
		$output = 'names'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'
		$post_types = get_post_types( $args, $output, $operator );
		foreach ($post_types as $post_type) {
			//Lowercase for class
			$lower_post_type = strtolower($post_type);
			$final_post_type_name = 'sidebar-support-settings-pt-'.$lower_post_type;
		   ?><p class="special"><input name="<?php echo $final_post_type_name?>" class="sidebar-support-settings-admin-input" type="checkbox"  id="<?php echo $final_post_type_name?>" value="1" <?php echo checked('1', get_option($final_post_type_name)); ?> /><?php echo $post_type ?></p> <?php
		}?> </div><!--/.pages-selections-wrap-->
		      <div class="clear"></div>
		    </div>
		    <!--/sidebar-support-settings-admin-input-wrap-->
		     <!-- custom option for padding -->
		    <div class="sidebar-support-settings-admin-input-wrap company-info-style sidebar-support-turn-on-custom-colors">
		      <div class="sidebar-support-settings-admin-input-label"><?php _e('Check the box to turn ON the custom options.', 'sidebar-support'); ?></div>
		    <p class="special">
		        <input name="sidebar-support-options-settings-custom-css-options" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-options-settings-custom-css-options" value="1" <?php echo checked( '1', get_option( 'sidebar-support-options-settings-custom-css-options' ) ); ?>/>
		        <?php
		                        if (get_option( 'sidebar-support-options-settings-custom-css-options' ) == '1') {
		                          _e('<strong>Checked:</strong> Custom style options being used now.', 'sidebar-support');
		                        }
		                        else	{
		                          _e('<strong>Not Checked:</strong> You are using the default styles.', 'sidebar-support');
		                        }
		                           ?>
		       </p>
		     <p>
		        <label><?php _e('Feed Width:', 'sidebar-support'); ?></label>
		        <input name="sidebar-support-main-wrapper-width-input" type="text"  id="sidebar-support-main-wrapper-width-input" placeholder="450px <?php _e('or', 'sidebar-support'); ?> 100% <?php _e('to fill screen', 'sidebar-support'); ?>" value="<?php echo get_option('sidebar-support-main-wrapper-width-input'); ?>" title="Only Numbers and px are allowed"/>
		      </p>
		    <div class="clear"></div>
		      <p>
		        <label><?php _e('Bar Position:', 'sidebar-support'); ?></label>
		         <div class="sidebar-support-custom-settings-checkbox-wrap">
		         <input name="sidebar-support-bottom-position" type="checkbox"  id="sidebar-support-bottom-position" value="1" <?php echo checked( '1', get_option( 'sidebar-support-bottom-position' ) ); ?>/>
		        <?php
		                        if (get_option( 'sidebar-support-bottom-position' ) == '1') {
		                            _e('FTS bar is in the bottom left corner', 'sidebar-support');
		                        }
		                        else	{
		                           _e('Show FTS bar in the bottom left corner', 'sidebar-support');
		                        }
		                           ?>
		         </div>
		      </p>
		    <div class="clear"></div>
		      <p>
		        <label><?php _e('Mobile:', 'sidebar-support'); ?></label>
		         <div class="sidebar-support-custom-settings-checkbox-wrap">
		         <input name="sidebar-support-options-mobile" type="checkbox"  id="sidebar-support-options-mobile" value="1" <?php echo checked( '1', get_option( 'sidebar-support-options-mobile' ) ); ?>/>
		        <?php
		                        if (get_option( 'sidebar-support-options-mobile' ) == '1') {
		                            _e('The FTS Bar is NOT visible on mobile', 'sidebar-support');
		                        }
		                        else	{
		                           _e('The FTS Bar is visible on mobile', 'sidebar-support');
		                        }
		                           ?>
		        </div>
		      </p>
		       <div class="clear"></div>
		      <p>
		        <label><?php _e('Tablet:', 'sidebar-support'); ?></label>
		         <div class="sidebar-support-custom-settings-checkbox-wrap">
		         <input name="sidebar-support-options-tablets" type="checkbox"  id="sidebar-support-options-tablets" value="1" <?php echo checked( '1', get_option( 'sidebar-support-options-tablets' ) ); ?>/>
		        <?php
		                        if (get_option( 'sidebar-support-options-tablets' ) == '1') {
		                            _e('The FTS Bar is NOT visible on tablets', 'sidebar-support');
		                        }
		                        else	{
		                           _e('The FTS Bar is visible on tablets', 'sidebar-support');
		                        }
		                           ?>
		        </div>
		      </p>
		    <div class="clear"></div>
		     <br/> <br/>
		     	<div class="sidebar-support-settings-admin-input-label"><?php _e('Check box to turn ON custom CSS options.', 'sidebar-support'); ?></div>
		        <p class="special">
		        <input name="sidebar-support-options-settings-custom-css-second" type="checkbox"  id="sidebar-support-options-settings-custom-css-second" value="1" <?php echo checked( '1', get_option( 'sidebar-support-options-settings-custom-css-second' ) ); ?>/>
		        <?php
		                        if (get_option( 'sidebar-support-options-settings-custom-css-second' ) == '1') {
		                            _e('<strong>Checked:</strong> Custom CSS option is being used now.', 'sidebar-support');
		                        }
		                        else	{
		                           _e('<strong>Not Checked:</strong> You are using the default CSS.', 'sidebar-support');
		                        }
		                           ?>
		       </p>
		       <p>
		         <label class="toggle-custom-textarea-show"><span><?php _e('Show', 'sidebar-support'); ?></span><span class="toggle-custom-textarea-hide"><?php _e('Hide', 'sidebar-support'); ?></span> <?php _e('custom CSS', 'sidebar-support'); ?></label>
		       <div class="sidebar-support-custom-css-text"><?php _e('<p>Add Your Custom CSS Code below.</p>', 'sidebar-support'); ?></div>
		      <textarea name="sidebar-support-settings-admin-textarea-css" class="sidebar-support-settings-admin-textarea-css" id="sidebar-support-main-wrapper-css-input"><?php echo get_option('sidebar-support-settings-admin-textarea-css'); ?></textarea>
		      </p>
		      <div class="clear"></div>
		    </div>
		    <!--/sidebar-support-settings-admin-input-wrap-->
		    <h3><?php _e('Custom Color Options', '	'); ?></h3>
		    <div class="sidebar-support-settings-admin-input-wrap company-info-style sidebar-support-turn-on-custom-colors">
		            <div class="view-all-custom"><a class="icon-view-all sidebar-support-color-options-open-close-all" href="#"><span class="view-all-articles"><?php _e('open / close', 'sidebar-support'); ?><br>
		            <?php _e('help photos', 'sidebar-support'); ?><span class="arrow-right"></span></span></a></div>
		      <div class="sidebar-support-settings-admin-input-label sidebar-support-wp-header-custom"><?php _e('Check box to turn on custom color options for the FTS Bar.', 'sidebar-support'); ?></div>
		      <p>
		        <input name="sidebar-support-settings-custom-css" class="sidebar-support-settings-admin-input" type="checkbox"  id="sidebar-support-settings-custom-css" value="1" <?php echo checked( '1', get_option( 'sidebar-support-settings-custom-css' ) ); ?>/>
		        <?php
		if (get_option( 'sidebar-support-settings-custom-css' ) == '1') {
		   _e('<strong>Checked:</strong> Custom styles being used now.', 'sidebar-support');
		}
		else	{
		  _e('<strong>Not Checked:</strong> You are using the default theme colors.', 'sidebar-support');
		}
		   ?>
		      </p>
		<a class="default-values-sidebar-support-pro-option1 sidebar-support-custom-color-btn" href="javascript:;"><?php _e('Set Default Colors', 'sidebar-support'); ?></a> <a class="default-values-sidebar-support-pro-option2 sidebar-support-custom-color-btn" href="javascript:;"><?php _e('Set Color Option 2', 'sidebar-support'); ?></a>
		      <div class="clear"></div>
		    </div>
		    <!--/sidebar-support-settings-admin-input-wrap-->
		    <div class="sidebar-support-float-wrap-2column sidebar-support-ct-color-options-wrap">
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Quick Responses Icon', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-quick-response-icon-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-quick-response-icon-color" value="<?php echo get_option('sidebar-support-quick-response-icon-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/quick-response-icon-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Quick Responses Icon Background', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-quick-response-background-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-quick-response-background-color" value="<?php echo get_option('sidebar-support-quick-response-background-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/quick-response-icon-backg-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Quick Links Icon', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-quick-links-icon-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-quick-links-icon-color" value="<?php echo get_option('sidebar-support-quick-links-icon-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/quick-links-icon-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Quick Links Icon Background', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-quick-links-background-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-quick-links-background-color" value="<?php echo get_option('sidebar-support-quick-links-background-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/quick-links-icon-backg-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Github Icon', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-github-icon-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-github-icon-color" value="<?php echo get_option('sidebar-support-github-icon-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/github-icon-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Github Icon Background', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-github-background-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-github-background-color" value="<?php echo get_option('sidebar-support-github-background-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/github-icon-backg-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Quick Responses Panel Background', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-quick-response-panel-background-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-quick-response-panel-background-color" value="<?php echo get_option('sidebar-support-quick-response-panel-background-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/quick-response-panel-backg-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		       <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Quick Links Panel Background', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-quick-links-panel-background-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-quick-links-panel-background-color" value="<?php echo get_option('sidebar-support-quick-links-panel-background-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/quick-links-panel-backg-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Github Panel Background', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-github-panel-background-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-github-panel-background-color" value="<?php echo get_option('sidebar-support-github-panel-background-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/github-panel-backg-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Panels Text', 'sidebar-support'); ?></div>
		        <input name="sidebar-support-text-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-text-color" value="<?php echo get_option('sidebar-support-text-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/panels-text-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Close Button', 'sidebar-support'); ?></div>
		         <input name="sidebar-support-close-button" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-close-button" value="<?php echo get_option('sidebar-support-close-button'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/close-button-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Quick Links Info Background', 'sidebar-support'); ?></div>
		         <input name="sidebar-support-quick-links-info-background" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-quick-links-info-background" value="<?php echo get_option('sidebar-support-quick-links-info-background'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/quick-links-info-backg-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		        <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('All Links', 'sidebar-support'); ?></div>
		         <input name="sidebar-support-link-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-link-color" value="<?php echo get_option('sidebar-support-link-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/links-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="sidebar-support-settings-admin-input-wrap company-info-style">
		        <div class="sidebar-support-settings-admin-input-label"><?php _e('Links Hover', 'sidebar-support'); ?></div>
		         <input name="sidebar-support-link-hover-color" class="sidebar-support-settings-admin-input color" type="text" id="sidebar-support-link-hover-color" value="<?php echo get_option('sidebar-support-link-hover-color'); ?>" />
		        <div class="sidebar-support-settings-admin-input-example"></div>
		        <div class="clear"></div>
		        <a class="sidebar-support-settings-toggle" href="#"></a>
		        <div class="sidebar-support-settings-id-answer"> <img src="<?php echo plugins_url( 'images/links-hover-sidebar-support.jpg' , __FILE__ ) ?>" alt="" /></div>
		        <!--/sidebar-support-settings-id-answer-->
		        <div class="clear"></div>
		      </div>
		      <!--/sidebar-support-settings-admin-input-wrap-->
		      <div class="clear"></div>
		    </div>
		    <!-- sidebar-support-float-wrap-2column -->
		    <input type="submit" class="sidebar-support-settings-admin-submit-btn" value="<?php _e('Save All Changes') ?>" />
		    <!-- <input class="sidebar-support-settings-admin-submit-btn" name="Reset" type="submit" value="< ?php _e('reset', sidebar-support-settings-title-color); ?>" />
		         <input name="action" type="hidden" value="reset" /> -->
		  </form>
		  <div class="sidebar-support-settings-icon-wrap"><a href="https://www.quick-response.com/SlickRemix" target="_blank" class="quick-response-icon"></a></div>
		  <a class="sidebar-support-settings-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
		</div>
		<!--/sidebar-support-settings-admin-wrap--> 
		      <div class="clear"></div>
		</div>
		<!--/sidebar-support-main-template-wrapper-all-->
		<script type="text/javascript" src="<?php echo plugins_url(); ?>/sidebar-support/admin/js/jscolor/jscolor.js"></script>
		<script type="text/javascript" src="<?php echo plugins_url(); ?>/sidebar-support/admin/js/admin.js"></script>
		<script>
		jQuery( document ).ready(function() {
		  jQuery( ".toggle-custom-textarea-show" ).click(function() {
				 jQuery('textarea#sidebar-support-main-wrapper-css-input').slideToggle('fast');
				  jQuery('.toggle-custom-textarea-show span').toggle();
				  jQuery('.sidebar-support-custom-css-text').toggle();
		});
		 jQuery( ".toggle-custom-textarea-show-terms" ).click(function() {
				 jQuery('textarea#sidebar-support-main-wrapper-custom-terms').slideToggle('fast');
				  jQuery('.toggle-custom-textarea-show-terms span').toggle();
				//  jQuery('.sidebar-support-custom-css-text').toggle();
		});
			jQuery('.fb-option-trigger').click(function() {
				jQuery('.sidebar-support-fb-options-toggle').slideUp();
				jQuery('.' + jQuery(this).data('rel')).slideToggle();
			});
			 jQuery( "#fb-bar-options-quick-response" ).click(function() {
			 	 jQuery('.sidebar-support-quick-links-options-wrap').slideUp();
				 jQuery('.sidebar-support-github-options-wrap').slideUp();
				 jQuery('.sidebar-support-quick-response-options-wrap').slideToggle('fast');
			});
			jQuery( "#fb-bar-options-quick-links" ).click(function() {
			 	 jQuery('.sidebar-support-quick-response-options-wrap').slideUp();
				 jQuery('.sidebar-support-github-options-wrap').slideUp();
				 jQuery('.sidebar-support-quick-links-options-wrap').slideToggle('fast');
			});
			jQuery( "#fb-bar-options-github" ).click(function() {
			 	 jQuery('.sidebar-support-quick-response-options-wrap').slideUp();
				 jQuery('.sidebar-support-quick-links-options-wrap').slideUp();
				 jQuery('.sidebar-support-github-options-wrap').slideToggle('fast');
			});
			 jQuery('#quick-links-messages-selector').bind('change', function (e) {
		    if( jQuery('#quick-links-messages-selector').val() == 'hashtag') {
		      jQuery(".hashtag-option-small-text").show();
		      jQuery(".quick-links-hashtag-etc-wrap").show();
		      jQuery(".hashtag-option-not-required, .must-copy-quick-links-name").hide();
		    }
			 else{
		      jQuery(".hashtag-option-not-required, .must-copy-quick-links-name").show();
		      jQuery(".quick-links-hashtag-etc-wrap").hide();
		      jQuery(".hashtag-option-small-text").hide();
		    }
		  });
		});
		//START convert Github name to id//
		function converter_github_username() {
			var convert_github_username = jQuery("input#convert_github_username").val();
			if (convert_github_username == "") {
			  	 jQuery(".convert_github_username").addClass('sidebar-support-empty-error');
		      	 jQuery("input#convert_github_username").focus();
				 return false;
			}
			if (convert_github_username  != "") {
			  	 jQuery(".convert_github_username").removeClass('sidebar-support-empty-error');
					var username = jQuery("input#convert_github_username").val();
					console.log(username);
					jQuery.getJSON("https://api.github.com/v1/users/search?q="+username+"&access_token=267791236.f78cc02.bea846f3144a40acbf0e56b002c112f8&callback=?",
					  {
						format: "json"
					  },
					  function(data) {
							console.log(data);
							var final_github_us_id = data.data[0].id;
							jQuery('#github_id').val(final_github_us_id);
							jQuery('.final-github-user-id-textarea').slideDown();
		   			 });
			}
		}
		</script>
	<?php
	}
}//END Class