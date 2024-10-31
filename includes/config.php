<?php
defined('ABSPATH') or die();

/* ADD SETTINGS PAGE
------------------------------------------------------*/
if( !function_exists('photobox_add_options_page') ){
	function photobox_add_options_page() {
		add_options_page(
			'Photo Box Settings',
			'Photo Box',
			'manage_options',
			'photobox-setting',
			'photobox_setting_display'
		);
	}
}
add_action('admin_menu','photobox_add_options_page');