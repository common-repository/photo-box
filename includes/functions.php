<?php
defined('ABSPATH') or die();

function photobox_url( $path = '' )
{
	return plugins_url( $path, photobox_index());
}

function photobox_ver()
{
	// return '2018.12.28.9.20';
	return '2019.06.22.15.30';
}

function photobox_assets_url( $path = '' )
{
	return photobox_url( 'media/'.$path );
}

function photobox_path( $path = '' )
{
	return dirname(photobox_index()). ( substr($path,0,1) !== '/' ? '/' : '' ) . $path;
}

function photobox_include( $path_file = '' )
{
	if( $path_file!='' && file_exists( $p = photobox_path('includes/'.$path_file ) ) ) {
		require $p;
		return true;
	}
	return false;
}

function photobox_pbone_url( $path = '' )
{
	$site = 'http://photoboxone.com/';

	$utm = 'utm_term=photo-box&utm_medium=photo-box&utm_source=' . urlencode( $_SERVER['HTTP_HOST'] );

	if( strpos( $path, '?' ) > -1 ) {
		$path .= '&';
	} else {
		$path .= '?';
	}
	
	return esc_url( $site . $path . $utm );
}

function photobox_wp_assets_url( $path = '' )
{
	return esc_url( 'https://ps.w.org/photo-box/assets'. ( substr($path,0,1) == '/' ? '' : '/' ) . $path );
}

/*
 * Use to Block Editor
 * 
 * @since 1.1.3
 * 
 * Add Style in edit Block Gallery
 * 
 */
function photobox_register_block_gallery_style() {
	
	wp_enqueue_script( 
		'photobox-block-gallery',
		photobox_url( 'media/block-gallery.js' ),
		array( 'wp-blocks', 'wp-hooks' ),
		photobox_ver(), 
		true
	);
	
}
add_action( 'admin_init', 'photobox_register_block_gallery_style' );
