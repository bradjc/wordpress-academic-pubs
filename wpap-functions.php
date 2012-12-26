<?php

	/*	
	*	Goodlayers Meta Template File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file contains the template of meta box for each input type.
	* 	The framework will use it when create meta box for each post_type.
	*	---------------------------------------------------------------------
	*/
	
	// decide to print each meta box type
	function wpap_print_option ($opt) {
	
		switch ($opt['type']) {
			case "inputtext": wpap_print_option_input_text($opt); break;
			case "upload":    wpap_print_meta_upload($opt); break;

		}
		
	}
	
	// nonce Verification	
	function wpap_set_nonce () {
	
		wp_nonce_field(plugin_basename(__FILE__), 'myplugin_noncename');
		
	}
	
	// text => name, title, value, default
	function wpap_print_option_input_text ($args) {

		?>
		
		<input type="text" name="<?php echo $args['name']; ?>" id="<?php echo $args['name']; ?>" value="<?php echo $args['value']; ?>" style="width:100%" />
		<p><?php echo $args['extra']; ?></p>
			
		<?php

	}

	// text => name, title, value
	function wpap_print_meta_upload ($args) {
	
	//	extract($args);
	
		$src = '';
		
		if (!empty($args['value'])) {
			$src = wp_get_attachment_url($args['value']);
		}
		
		?>
		
		<input name="<?php echo $args['name']; ?>" type="hidden" id="upload_image_attachment_id" value="<?php echo esc_html($args['value']); ?>" />
		<input id="upload_image_text_meta" type="text" value="<?php echo $src; ?>" size="30" />
		<input class="upload_image_button_meta" type="button" value="Upload" />
		
		<p><?php echo $args['extra']; ?></p>
		
		<?php
		
	}

	// save option function that trigger when saveing each post
	add_action('save_post', 'wpap_save_option_meta');
	function wpap_save_option_meta ($post_id) {
	
		// Verification
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!isset($_POST['myplugin_noncename'])) return;
		if (!wp_verify_nonce($_POST['myplugin_noncename'], plugin_basename( __FILE__ ))) return;
		
		if ($_POST['post_type'] == 'publication') {
			if (!current_user_can('edit_post', $post_id)) return;
			
			wpap_save_publication_option_meta($post_id);
		}
		
	}
	
	// function that save the meta to database if new data is exists and is not equals to old one
	function wpap_save_meta_data ($post_id, $new_data, $old_data, $name) {

		if($new_data == $old_data){
		
			add_post_meta($post_id, $name, $new_data, true);
			
		}else if(!$new_data){
		
			delete_post_meta($post_id, $name, $old_data);
			
		}else if($new_data != $old_data){

			update_post_meta($post_id, $name, $new_data, $old_data);
			
		}
	}

?>