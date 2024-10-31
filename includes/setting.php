<?php
defined('ABSPATH') or die();

$pagenow 	= sanitize_text_field( isset($GLOBALS['pagenow'])?$GLOBALS['pagenow']:'' );

if( $pagenow == 'plugins.php' ){
	
	function photobox_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
		$url_setting = admin_url('options-general.php?page=photobox-setting');
		
		array_unshift($actions, '<a href="http://photoboxone.com/donate" target="_blank">'.__("Donate", 'photo-box')."</a>");
		array_unshift($actions, '<a href="'.photobox_pbone_url('contact').'" target="_blank">'.__("Support", 'photo-box')."</a>");
		array_unshift($actions, "<a href=\"$url_setting\">".__("Settings", 'photo-box')."</a>");
		
		return $actions;
	}
	
	add_filter("plugin_action_links_".plugin_basename(photobox_index()), "photobox_plugin_actions", 10, 4);

}

if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ){
	
	photobox_include('admin.php');
	
}

/* SECTIONS - FIELDS
------------------------------------------------------*/
function photobox_init_theme_opotion() {
	$pagenow = sanitize_text_field( isset($GLOBALS['pagenow'])?$GLOBALS['pagenow']:'' );
	
	// add Setting
	add_settings_section(
		'photobox_options_section',
		'PhotoBox Options',		
		'photobox_options_section_display',
		'photobox-options-section'
	);
	
	register_setting( 'photobox_settings','photobox_options');
	
	// Styles
	wp_enqueue_style( 'photobox-setting-admin', photobox_url('/media/admin.css') );
	
	// Scripts
	if( $pagenow == 'options-general.php' ) {
		wp_enqueue_media();
		wp_enqueue_script('photobox-setting-upload', photobox_url( 'media/admin-min.js' ) , array('jquery'), photobox_ver(), true);
	}
	
}
add_action('admin_init', 'photobox_init_theme_opotion');

/* CALLBACK
------------------------------------------------------*/
function photobox_setting_display(){
	
	$options = shortcode_atts(array(
		'disable_style'	=> 0,
		'autopopup_media' => 0,
		'autopopup_times' => 1000,
		'autopopup_link' => '',
		'autopopup_link_target' => '',
		'popup_all_image_links' => 0
	), (array)get_option('photobox_options'));

	$tab 		= sanitize_text_field( isset($_GET['tab']) ? $_GET['tab'] : '' );
	$url_setting = admin_url('options-general.php?page=photobox-setting');


?>
	<h2><?php _e( 'Photo Box - Setting', 'photo-box' ); ?></h2>
	<div class="wrap photobox_settings clearfix">
		<div class="photobox_advanced clearfix">
			<div class="photobox_tabmenu clearfix">
				<ul>
					<li <?php echo $tab==''?' class="active"':'';?>>
						<a href="<?php echo $url_setting?>"><?php _e( 'General', 'photo-box' ); ?></a>
					</li>
					<!-- <li <?php echo $tab=='block'?' class="active"':'';?>>
						<a href="<?php echo $url_setting.'&tab=block'?>"><?php _e( 'Block Edit', 'photo-box' ); ?></a>
					</li> -->
				</ul>
			</div>
			<div class="photobox_tabitems clearfix">
				<div class="photobox_tabitem item-1<?php echo $tab==''?' active':'';?>">
					<?php photobox_setting_form($options); ?>
				</div>
				<div class="photobox_tabitem item-2<?php echo $tab=='block'?' active':'';?>">
					<?php photobox_block_form($options); ?>
				</div>
			</div>
		</div>

		<div class="photobox_sidebar clearfix">
			<?php 
				photobox_help_links(); 
				photobox_donate_text();
			?>
		</div>
	</div>
<?php
}

function photobox_setting_form( $options = array() )
{
	extract($options);
	// var_dump($options);
?>
	<form action="options.php" method="post">
		<?php settings_fields('photobox_settings' ); ?>
		<p>
			<label for="photobox_options_disable_style"><?php _e( 'Disable style of photo box', 'photo-box' ); ?></label>
			<select name="photobox_options[disable_style]" id="photobox_options_disable_style">
				<option value="0"><?php _e( 'No', 'photo-box' ); ?></option>
				<option value="1"<?php echo ($disable_style?" selected":"");?>><?php _e( 'Yes', 'photo-box' ); ?></option>
			</select>
		</p>
		<p>
			<label for="photobox_options_popup_all_image_links"><?php _e( 'Set all links are images', 'photo-box' ); ?>:</label>
			<select name="photobox_options[popup_all_image_links]" id="photobox_options_popup_all_image_links">
				<option value="0"><?php _e( 'No', 'photo-box' ); ?></option>
				<option value="1"<?php echo ($popup_all_image_links?" selected":"");?>><?php _e( 'Yes', 'photo-box' ); ?></option>
			</select>
		</p>
		<h3><?php _e( 'Auto popup in Home Page', 'photo-box' ); ?></h3>
		<p id="photobox_options_image_thumb"><?php echo ($autopopup_media>0?wp_get_attachment_image($autopopup_media,'thumbnail','',array('height' => 150) ):'');?></p>
		<p>
			<input value="<?php echo $autopopup_media;?>" type="hidden" name="photobox_options[autopopup_media]" id="photobox_options_image_id" />
			<button id="photobox_options_upload_image_button"><?php _e( 'Choose Image', 'photo-box' ); ?></button>
			<button id="photobox_options_remove_image_button"><?php _e( 'Remove Image', 'photo-box' ); ?></button>
		</p>
		<p>
			<label for="photobox_options_autopopup_link"><?php _e( 'Link', 'photo-box' ); ?>:</label>
			<input value="<?php echo $autopopup_link;?>" type="url" name="photobox_options[autopopup_link]" id="photobox_options_autopopup_link" size="75" />
		</p>
		<p>
			<label for="photobox_options_autopopup_link_target"><?php _e( 'Link target', 'photo-box' ); ?>:</label>
			<select name="photobox_options[autopopup_link_target]" id="photobox_options_autopopup_link_target">
				<option value=""><?php _e( 'None', 'photo-box' ); ?></option>
				<option value="_blank"<?php echo ($autopopup_link_target=="_blank"?" selected":"");?>><?php _e( 'New Tab', 'photo-box' ); ?></option>
			</select>
		</p>
		<p>
			<label for="photobox_options_autopopup_times"><?php _e( 'Number of popup', 'photo-box' ); ?>:</label>
			<input value="<?php echo $autopopup_times;?>" type="text" name="photobox_options[autopopup_times]" id="photobox_options_autopopup_times" /> (<?php _e( 'times', 'photo-box' ); ?>).
		</p>
		<?php submit_button(); ?>
	</form>
<?php
}

function photobox_block_form( $options = array() )
{
	extract($options);
	// var_dump($options);
?>
	<form action="options.php" method="post">
		<?php settings_fields('photobox_settings' ); ?>
		<p>
			<label for="photobox_options_autopopup_times"><?php _e( 'Number of popup', 'photo-box' ); ?>:</label>
			<input value="<?php echo $autopopup_times;?>" type="text" name="photobox_options[autopopup_times]" id="photobox_options_autopopup_times" /> (<?php _e( 'times', 'photo-box' ); ?>).
		</p>
		<?php submit_button(); ?>
	</form>
<?php
}

function photobox_help_links( $show = false )
{
?>
	<div class="photobox_sidebar_box" >
		<h4><?php _e( 'Do you need help?', 'photo-box' ); ?></h4>
		<ol>
			<li>
				<a href="<?php echo photobox_pbone_url('photo-box-plugin');?>" target="_blank" rel="help" >
					<?php _e( 'Help', 'photo-box' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo photobox_pbone_url('contact');?>" target="_blank" rel="help">			
					<?php _e( 'Support', 'photo-box' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo photobox_pbone_url();?>" target="_blank" rel="author">
					<?php _e( 'About', 'photo-box' ); ?>
				</a>
			</li>
		</ol>
	</div>
<?php
}

function photobox_donate_text()
{
?>
	<div class="photobox_sidebar_box">
		<h4>
			<?php _e( 'You can donate to us by visiting our website. Thank you for watching.', 'photo-box' ); ?>
		</h4>
		<p>
			<div class="photobox-icon-click">
				<div class="dashicons dashicons-arrow-right-alt"></div>
			</div>
			<a href="<?php echo photobox_pbone_url('photo-box-plugin');?>" target="_blank" rel="help">
				<?php _e( 'Visiting our website', 'photo-box' ); ?>
			</a>
		</p>
		<p>
			<?php _e( 'You can donate by PayPal.', 'photo-box' ); ?>
		</p>
		<p align=center>
			<a href="http://photoboxone.com/donate?for=photo-box" target="_blank" rel="help" class="button button-primary">	
				<?php _e( 'Donate', 'photo-box' ); ?>
			</a>
		</p>
		<p>
			<?php _e( 'Thank you for using Photo Box.', 'photo-box' ); ?>
		</p>
	</div>
<?php
}

