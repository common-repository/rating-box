<?php


add_action( 'wp_ajax_submit_star', 'rating_box_ajax_submit_star' );
add_action( 'wp_ajax_nopriv_submit_star', 'rating_box_ajax_submit_star' );
function rating_box_ajax_submit_star() {
	// Handle request then generate response using WP_Ajax_Response
	$json = array();
	
	if( empty($_POST['post_id']) || empty($_POST['star_value']) ){
		$json['code'] = 403;
		
		die(json_encode($json));
	}
	
	$post_id 		= intval($_POST['post_id']);
	$star_value 	= intval($_POST['star_value']);
	
	// set cookie_id
	$cookieName = md5('rating_box_cookie_id');
	
	if( isset($_COOKIE[$cookieName]) ){
		$cookie_id = $_COOKIE[$cookieName];
	} else {
		$cookie_id = md5(time());
		@setcookie( $cookieName, $cookie_id, strtotime( '+1 year' ), '/' );		
	}
	
	global $wpdb, $table_prefix;
	$table = $table_prefix.'rating_box';
	
	$user_count = (int)$wpdb->get_var( "SELECT count(id) FROM `$table` WHERE `cookie_id`='$cookie_id' AND `post_id`='$post_id' LIMIT 0,1" );
	if( $user_count > 0 ){
		$json['code'] = 403;
		$json['message'] = 'You have already rated !';
		
		die(json_encode($json));
	}
	
	$created = date('Y-m-d H:i:s');

	$wpdb->insert( 
					$table, 
					array( 
						'star_value' => $star_value ,
						'post_id' => $post_id ,
						'cookie_id' => $cookie_id ,
						'created' => $created ,
					), 
					array( 
						'%d',
						'%d',
						'%s', 
						'%s', 
					) 
				);
	
	if( $wpdb->insert_id>0 ){
		$json['code'] = 200;
		$json['message'] = 'Rated successful!';
	} else {
		$json['code'] = 503;
		$json['message'] = 'Rated faile!';
	}
	
	die(json_encode($json));
}