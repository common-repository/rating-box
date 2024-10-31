<?php
defined('ABSPATH') or die('<meta http-equiv="refresh" content="0;url='.WP_RB_URL_AUTHOR.'">');
/*
 * Author: http://photoboxone.com
 */
if( !function_exists('rating_box_html') ):
function rating_box_html( $post_id = 0 ){
	extract(shortcode_atts(array(
		'style'	=> 'fontawesome',
	), (array)get_option('rating_box_display')));
	
	$html = '<div class="rbjs-rating-box rating-box-plugin style-'.$style.' clearfix" data-post-id="'.$post_id.'">';
	
	if( $post_id>0 ){
		$star_value = rating_box_get_data($post_id);
		
		$html .= '<ul>';
		for($i=1;$i<=5;$i++){
			$html .= '<li data-index="'.$i.'" '.($i<=$star_value?' class="active"':'').'>';
			
			
			if( $style == 'fontawesome' ){		
				$html .= '<i class="fa fa-star" aria-hidden="true"></i>';
			} else {
				$html .= $i;
			}
			
			
			$html .= '</li>';
		}
		$html .= '</ul>';
	}
	
	$html .= '</div>';
	
	return $html;
}
endif;

if( !function_exists('rating_box_get_data') ):
function rating_box_get_data( $post_id = 0 ){
	$star_value = 0;
	
	if( $post_id>0 ){
		
		global $wpdb, $table_prefix;
		$table = $table_prefix.'rating_box';
		
		$rating_box_display = (array)get_option('rating_box_display');
		if( empty($rating_box_display['created_table']) || intval($rating_box_display['created_table']) == 0 ){
			rating_box_created_table( $wpdb, $table);
			
			$rating_box_display['created_table'] = 1;
			update_option('rating_box_display', $rating_box_display);
		}
		
		// $items = $wpdb->get_results( "SELECT count(id) AS `count`, SUM(`star_value`) AS `total` FROM `$table` WHERE `post_id`=$post_id " );
		$row = $wpdb->get_row( "SELECT count(id) AS `count`, SUM(`star_value`) AS `total` FROM `$table` WHERE `post_id`=$post_id " );
		if( is_object($row) && isset($row->total) && intval($row->total)>0 ){
			$star_value = round(intval($row->total)/intval($row->count),0);
		}
		
	}
	
	return $star_value;
}
endif;

if( !function_exists('rating_box_created_table') ):
function rating_box_created_table( $wpdb, $table){
	// echo "CREATE TABLE IF NOT EXISTS `$table`;";
	
	$wpdb->query( "CREATE TABLE IF NOT EXISTS `$table` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `star_value` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );

}
endif;

if( !function_exists('rating_box_filter_title') ):
function rating_box_filter_title( $title, $id = null ){
	
	$title .= rating_box_html();
	
	
	return $title;
}
endif;
//add_filter( 'the_title', 'rating_box_filter_title', 10, 2 );

if( !function_exists('rating_box_filter_content') ):
function rating_box_filter_content( $content ){
	extract(shortcode_atts(array(
		'position'	=> '',
	), (array)get_option('rating_box_display')));
	
	
	// assuming you have created a page/post entitled 'debug'
	if ( $GLOBALS['post']->post_name == 'debug') {
		
	}
	
	if ( is_single() ){
		if( $position == 'Top Content' ){
			$content = rating_box_html($GLOBALS['post']->ID) . $content;	
		}
		
		if( $position == 'Bottom Content' ){
			$content .= rating_box_html($GLOBALS['post']->ID);		
		}
	}
	
	// otherwise returns the database content
	return $content;
}
endif;
add_filter( 'the_content', 'rating_box_filter_content', 10, 2 );

/*
 * 
 */
if ( ! function_exists( 'rating_box_style' ) ) :
function rating_box_style() {
	extract(shortcode_atts(array(
		'style'	=> 'fontawesome',
	), (array)get_option('rating_box_display')));
	
	echo '<!-- Rating Box - Wordpress Plugins at http://photoboxone.com -->'."\n";
	echo '<link id="rating-box-style" rel="stylesheet" href="'.WP_RB_URL. 'media/rating.css" />'."\n";
	
	if( $style == 'fontawesome' ){
		echo '<link id="maxcdn-bootstrapcdn-font-awesome" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />'."\n";
	}
	echo '<script type="text/javascript">var admin_ajax_url = "'.admin_url('/admin-ajax.php').'";</script>'."\n";
	
}
endif; // main_setup
add_action( 'wp_head', 'rating_box_style' );

if ( ! function_exists( 'rating_box_script' ) ) :
function rating_box_script() {
	echo '<script id="rating-box-script" type="text/javascript" src="'.WP_RB_URL. 'media/rating.js"></script>'."\n";
}
endif;
add_action('print_footer_scripts', 'rating_box_script', 99 );
