<?php
/*
Plugin Name: Photo Box
Plugin URI: http://photoboxone.com
Description: Photo Box is a plugin view images popup, auto popup in homepage, slide show popup, responsive popup, slider for gallery in posts, pages, shortcode.
Author: Photoboxone
Author URI: http://photoboxone.com
Version: 1.2.6
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') or die();

function photobox_index()  
{
	return __FILE__;
}

require( dirname(__FILE__). '/includes/functions.php');

photobox_include('config.php');

if( is_admin() ){ 
	 
	photobox_include('setting.php');
	
} else {
	
	photobox_include('site.php');
	
}