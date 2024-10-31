<?php
defined('ABSPATH') or die;

/* ADD SETTINGS PAGE
------------------------------------------------------*/
if( !function_exists('rating_box_add_options_page') ){
	function rating_box_add_options_page() {
		add_options_page(
			'Rating Box Settings',			// The text to be displayed in the browser title bar
			'Rating Box',					// The text to be used for the menu
			'manage_options',				// The required capability of users to access this menu
			'rating-box-setting',			// The slug by which this menu item is accessible
			'rating_box_setting_display'	// The name of the function used to display the page content
		);
	}
}
add_action('admin_menu','rating_box_add_options_page');