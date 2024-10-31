<?php
defined('ABSPATH') or die;

/* SECTIONS - FIELDS
------------------------------------------------------*/
if( !function_exists('rating_box_init_theme_options') ):
function rating_box_init_theme_options() {
	// 
	add_settings_section(
		'rating_box_display_section', 				// The ID to use for this section
		'Display Options',							// Title of this section
		'rating_box_display_section_display',		// Function Callback
		'rating_box-display-section'					// The ID use when render
	);
	register_setting( 'rating_box_settings','rating_box_display');
	
	
}
endif;
add_action('admin_init', 'rating_box_init_theme_options');

/* CALLBACK
------------------------------------------------------*/
if( !function_exists('rating_box_setting_display') ):
function rating_box_setting_display(){ 
	extract(shortcode_atts(array(
		'position' => '',
	), (array)get_option('rating_box_display')));
	
?>
	<style>
	.rating_box_links li{
		display: inline;
		margin: 0 10px 0 0;
	}
	</style>
	
	<div class="wrap rating_box_settings clearfix">
		<?php screen_icon() ?>
		<h2>Rating Box</h2>
		<?php rating_box_links(); ?>
		<div class="rating_box_advanced clearfix">
			<h3>Settings</h3>
			<form action="options.php" method="post">
				<?php settings_fields('rating_box_settings' ); ?>
				<p>
					<label for="rating_box_display_position">Position: </label>
					<select name="rating_box_display[position]">
					<?php foreach(array('Top Content', 'Bottom Content') as $v ):?>
					<option value="<?php echo $v;?>"<?php echo $v==$position?' selected':'';?>><?php echo $v;?></option>
					<?php endforeach;?>
					</select>
				</p>
				
				<?php submit_button(); ?>
			</form>
		</div>
		
		<?php rating_box_links(); ?>
	</div>
<?php
}
endif;

if( !function_exists('rating_box_links') ):
function rating_box_links(){
?>
	<div class="rating_box_links clearfix">
		<ul>
			<li class="first"><a target="_blank" href="http://photoboxone.com/" title="PhotoBox Plugin Wordpress">Home</a></li>
			<li><a target="_blank" href="http://photoboxone.com/category/documents/" title="Documents Wordpress">Documents</a></li>
			<li><a target="_blank" href="http://photoboxone.com/donate/">Donate</a></li>
		</ul>
	</div>
<?php
}
endif;