<?php
defined('ABSPATH') or die();

// photobox_gallery_setting
if( !function_exists('photobox_gallery_setting') ):
function photobox_gallery_setting(){
	// script for media popup
	?>
	<script type="text/html" id="tmpl-gallery-photo-box-setting">
		<label class="setting">
			<span><?php _e('Type', 'photo-box'); ?></span>
			<select data-setting="type">
				<option value="default"><?php _e("Default", 'photo-box')?></option>
				<option value="photobox"><?php _e("Photo Box", 'photo-box')?></option>
			</select>
		</label>
		<label class="setting">
			<span><?php _e('Show Title', 'photo-box'); ?></span>
			<select data-setting="show_title">
				<option value="1"><?php _e("Yes", 'photo-box')?></option>
				<option value="0"><?php _e("No", 'photo-box')?></option>
			</select>
		</label>
		<label class="setting">
			<span><?php _e('Slideshow Speed', 'photo-box'); ?></span>
			<input data-setting="slideshow_speed" type="text" value="2000" style="width:100px; "/>
		</label>
		<label class="setting">
			<span><?php _e('Use background image', 'photo-box'); ?></span>
			<select data-setting="use_background">
				<option value="0"><?php _e("No", 'photo-box')?></option>
				<option value="1"><?php _e("Yes", 'photo-box')?></option>
			</select>
		</label>
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			_.extend(wp.media.gallery.defaults, {
				type: 'default',
				show_title: 1,
				use_background: 0,
				slideshow_speed: 2000
			});
			wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
				template: function(view){
					return wp.media.template('gallery-settings')(view)
							 + wp.media.template('gallery-photo-box-setting')(view);
				}
			});
		});
	</script>
	<?php
}
endif;
add_action( 'print_media_templates', 'photobox_gallery_setting', 10, 99  );