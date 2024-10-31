<?php
defined('ABSPATH') or die();
/*
 * photobox_shortcode
 */
if( !function_exists('photobox_shortcode') ):
function photobox_shortcode($val, $attr){
	$post = get_post();
	
	static $instance = 0;
	$instance++;	
	
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		
		// Use for photobox
		'show_title' => 1,
		'type' => '',
		'slideshow_speed' => 2500,
		'use_background' => 0,
		'testing' => 0,
	), $attr));
	
	// if type not photo-box no use
	if( $type != 'photobox' ) return '';
	
	$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	$attachments = array();
	foreach ( $_attachments as $key => $val ) {
		$attachments[$val->ID] = $_attachments[$key];
	}
	if ( empty($attachments) )
		return '';
	
	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}
	
	if( empty($testing) ) $testing = 0;
	
	$output = '';
	if( $count = count($attachments) ){
		$j = 0;
		$i = 0;
		
		$array = array(
						'name' => '#photobox-gallery-'.$instance,
						'setting' => array(
							'rel' => 'photobox-gallery-'.$instance,
							'slideshow' => true,
							'slideshowAuto' => false,
							'slideshowSpeed' => $slideshow_speed,
							'maxWidth' => "95%",
							'maxHeight' => "95%",
							'photo' => true,
							'slideshowStart' => " ",
							'slideshowStop' => " " 
						),
					);
		
		$output .= '<div id="photobox-gallery-'.$instance.'" '
					.' data-setting="'. substr( str_replace('"',"'",json_encode($array['setting'])), 1, -1 ) .'" '
					.' class="photobox-galleries gallery-photo-box gallery galleryid-'.$id.' gallery-columns-'.$columns.' clearfix">';
			$output .= '<div class="gallery-row clearfix">';
			foreach($attachments as $attachment){
				$j++;
				$i++;
				$output .= '<div class="gallery-image gallery-image-'.$j.' gallery-image-i-'.$i.'">';				
					$output .= '<a class="photobox" rel="gallery-'.$instance.'" title="'.$attachment->post_title.'" href="'.$attachment->guid.'">';
					if( $use_background == 1 ){
						$image_srcs = wp_get_attachment_image_src( $attachment->ID, $size ); // returns an array
						$output .= '<span class="image_thumb" style="background-image:url('.$image_srcs[0].');"></span>';
					} else { 
						$output .= wp_get_attachment_image( $attachment->ID, $size );
					}
					if( $show_title ){
						$output .= '<span class="image_title">'.$attachment->post_title.'</span>';
					}
					$output .= '</a>';
				$output .= '</div>';
				
				if( $i%$columns==0 && $i<$count ){
					$output .= '<br style="clear:both;" />';
					$output .= '</div><div class="gallery-row clearfix">';
					$j = 0;
				}
			}
			$output .= '<br style="clear:both;" /></div>';
		$output .= '</div>';
	}
	return $output;
}
endif;
add_filter('post_gallery', 'photobox_shortcode', 10, 99);

if ( ! function_exists( 'photobox_enqueue_scripts' ) ) :
function photobox_enqueue_scripts() {
	
	$photobox_options = shortcode_atts(array(
		'disable_style'			=> 0,
		'popup_all_image_links' => 1,
		'autopopup_media' 		=> 0,
		'autopopup_image_url' 	=> '',
		'autopopup_times' 		=> 1000,
		'autopopup_link' 		=> '',
		'autopopup_link_target' => ''
	), (array)get_option('photobox_options') );
	
	extract($photobox_options);
	
	// Styles
	wp_enqueue_style( 'photobox-style', photobox_assets_url('colorbox.css'), '', '' );
	if( $disable_style == 0 ){
		wp_enqueue_style( 'photobox-site', photobox_assets_url('site.css'), '', '' );
	}

	// Scripts
	wp_enqueue_script( 'photobox', photobox_assets_url('jquery.photobox-min.js'),  array('jquery'), photobox_ver(), true );
	
	if( $autopopup_media > 0 ){
		$image_url = wp_get_attachment_image_url( $autopopup_media, $size = 'full' );
		$photobox_options['autopopup_image_url'] = $image_url;
	}	
	wp_localize_script( 'photobox', 'photobox_setting', $photobox_options );
	
}
endif; // photobox_enqueue_scripts
add_action( 'wp_enqueue_scripts', 'photobox_enqueue_scripts' );

/*
 * Use to Block Editor
 * 
 * @since 1.1.3
 * 
 * filter when render Block Gallery in site (front-end)
 * 
 */
function photobox_pre_render_block( $null, $block ) 
{
	return null;
}
// add_filter('pre_render_block', 'photobox_pre_render_block', 10, 99 );

function photobox_render_block_data( $content_block, $block )
{
	
	// $json = (array) json_decode( file_get_contents( ABSPATH.'/note.txt' ), true );

	// $json = array_merge( $json, json_encode( array( $block, $source_block ) ) );

	// $c = file_get_contents( ABSPATH.'/note.txt' );

	// file_put_contents( ABSPATH.'/note.txt', $c . "\n\r" . json_encode( array( $block, $source_block ) ) );

	return $content_block;
}
// add_filter('render_block', 'photobox_render_block_data', 10, 99 );