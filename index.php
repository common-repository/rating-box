<?php
/*
Plugin Name: Rating Box
Description: Rating Box is a plugin show rating in post, page, ...
Plugin URI: https://wordpress.org/plugins/rating-box
Author: PB One
Author URI: http://photoboxone.com/
Version: 1.0.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('ABSPATH') or die;

define('WP_RB_URL_AUTHOR', 'http://photoboxone.com/' ); 

define('WP_RB_PATH', dirname(__FILE__) ); 
define('WP_RB_PATH_INCLUDES', dirname(__FILE__).'/includes' ); 
define('WP_RB_PATH_THEMES', dirname(__FILE__).'/themes' ); 
define('WP_RB_URL', plugins_url('', __FILE__).'/' ); 
define('WP_RB_URL_THEMES', WP_RB_URL.'themes/' ); 
define('WP_RB_URL_IMAGES', WP_RB_URL.'images/' ); 
define('WP_RB_URL_MEDIA', WP_RB_URL.'media/' );

require WP_RB_PATH_INCLUDES.'/functions.php';

if( is_admin() ){
	require WP_RB_PATH_INCLUDES.'/config.php';
	
	$action = isset($_GET['action'])?$_GET['action']:'';
	$page 	= isset($_GET['page'])?$_GET['page']:'';
	$post_new = preg_match('/post-new.php/i',$_SERVER['REQUEST_URI']);
	$plugins = preg_match('/plugins.php/i',$_SERVER['REQUEST_URI']);
	$options = preg_match('/options/i',$_SERVER['REQUEST_URI']);
	
	
	if( $plugins ){
		function rating_box_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
			$url_setting = admin_url('options-general.php?page=rating-box-setting');
			
			//array_unshift($actions, "<a href=\"http://photoboxone.com/download\" target=\"_blank\">".__("Full Version")."</a>");
			array_unshift($actions, "<a href=\"http://photoboxone.com/documents\" target=\"_blank\">".__("Documents")."</a>");
			array_unshift($actions, "<a href=\"$url_setting\">".__("Settings")."</a>");
			return $actions;
		}
		
		add_filter("plugin_action_links_".plugin_basename(__FILE__), "rating_box_plugin_actions", 10, 4);
	}
	
	if( $options ){
		require WP_RB_PATH_INCLUDES.'/setting.php';
	}
	
} else {
	require WP_RB_PATH_INCLUDES.'/site.php';
}


