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
	
	//	if(empty($meta_box['default'])) $meta_box['default'] = '';
		
		switch ($opt['type']) {
		//	case "open" : print_meta_open_div($meta_box); break;
		//	case "close" : print_meta_close_div($meta_box); break;
		//	case "header": print_meta_header($meta_box); break;
		//	case "text": print_meta_text($meta_box); break;
		//	case "description": print_description($meta_box); break;
			case "inputtext": wpap_print_option_input_text($opt); break;
			case "upload":    wpap_print_meta_upload($opt); break;
		//	case "textarea": print_meta_input_textarea($meta_box); break;
		//	case "checkbox": print_meta_input_checkbox($meta_box); break;
		//	case "combobox": print_meta_input_combobox($meta_box); break;
		//	case "radioenabled": print_meta_input_radioenabled($meta_box); break;
		//	case "radioimage": print_meta_input_radioimage($meta_box); break;
		//	case "imagepicker": print_image_picker($meta_box); break;

		}
		
	}
	
	// nonce Verification	
	function wpap_set_nonce () {
	
		wp_nonce_field(plugin_basename(__FILE__), 'myplugin_noncename');
		
	}
	
	// header => name, title
/*	function print_meta_header($args){
	
		extract($args);
		$meta_id = (isset($meta_id))? $meta_id : '';
		
		?>	
			
			<div id="meta-header" class="<?php echo $meta_id; ?>">
				<h2><?php _e($title, 'gdl_back_office'); ?></h2>
			</div>
			
		<?php 
		
	}

	// text => name, text
	function print_meta_text($args){
	
		extract($args); 
		
		?>
		
			<div class="meta-body">
				<div class="meta-title pb10">
					<?php _e($title, 'gdl_back_office'); ?>
				</div>
			</div>
			
		<?php 
		
	}
*/	
	// text => name, title, value, default
	function wpap_print_option_input_text ($args) {

		?>
		
		<input type="text" name="<?php echo $args['name']; ?>" id="<?php echo $args['name']; ?>" value="<?php echo $args['value']; ?>" style="width:100%" />
		<p><?php echo $args['extra']; ?></p>
			
		<?php

	}
/*
	// text => name, title, value, default
	function print_description($args){
		extract($args);
		
		?>
		
			<div class="meta-body">
				<div class="meta-title">
					<label><?php _e($title, 'gdl_back_office'); ?></label>
				</div>
				<div class="only-description"> <?php echo $description; ?> </div>
				<br class=clear>
			</div>
			
		<?php
		
	}	
*/
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
/*	
	// textarea => name, title, value, default
	function print_meta_input_textarea($args){
	
		extract($args);
		
		?>
		
			<div class="meta-body <?php echo str_replace('[]','',$name); ?>-wrapper">
				<div class="meta-title">
					<label for="<?php echo $name; ?>"><?php _e($title, 'gdl_back_office'); ?></label>
				</div>
				<div class="meta-input">
					<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" class="<?php echo str_replace('[]','',$name); ?>"><?php
												
						echo ($value == '')? esc_html($default): esc_html($value);
						
					?></textarea>
				</div>
				
				<?php if(isset($description)){ ?>
				
					<div class="meta-description"><?php echo $description; ?></div>
					
				<?php } ?>
				
				<br class="clear">
			</div>
			
		<?php
		
	}
	
	// checkbox => name, title, value
	function print_meta_input_checkbox($args){
	
		extract($args);
		
		?>
		
			<div class="meta-body">
				<div class="meta-title">
					<label for="<?php echo $name; ?>"><?php _e($title, 'gdl_back_office'); ?></label>
				</div>
				<div class="meta-input">
					Not yet implement
				</div>
				
				<?php if(isset($description)){ ?>
				
					<div class="meta-description"><?php echo $description; ?></div>
					
				<?php } ?>
				
				<br class=clear>
			</div>
			
		<?php
	}	
	
	// combobox => name, title, value, options[]
	function print_meta_input_combobox($args){
	
		extract($args);
		
		$value = (empty($value))? $default: $value;
		
		?>
			
			<div class="meta-body">
				<div class="meta-title">
					<label for="<?php echo $name; ?>"><?php _e($title, 'gdl_back_office'); ?></label>
				</div>
				<div class="meta-input">	
					<div class="combobox">
						<select name="<?php echo $name; ?>" id="<?php echo str_replace('[]', '', $name); ?>">
						
							<?php foreach($options as $option){ ?>
							
								<option rel="<?php echo $option ; ?>" <?php if( $option==esc_html($value) ){ echo 'selected'; }?> ><?php echo $option ; ?></option>
						
							<?php } ?>
							
						</select>
					</div>
				</div>
				
				<?php if(isset($description)){ ?>
				
					<div class="meta-description"><?php echo $description; ?></div>
					
				<?php } ?>
				
				<br class=clear>
			</div>
			
		<?php
		
	}	
	
	// radioenabled => name, title, value
	function print_meta_input_radioenabled($args){
	
		extract($args);
		
		?>
		
			<div class="meta-body">
				<div class="meta-title">
					<label for="<?php echo $name; ?>"><?php _e($title, 'gdl_back_office'); ?></label>
				</div>
				<div class="meta-input">
					<input type="radio" name="<?php echo $name; ?>" value="enabled" <?php if($value=='enabled' || $value=='') echo 'checked'; ?>> Enable &nbsp&nbsp&nbsp
					<input type="radio" name="<?php echo $name; ?>" value="disable" <?php if($value=='disable') echo 'checked'; ?>> Disable
				</div>
				
				<?php if(isset($description)){ ?>
				
					<div class="meta-description"><?php echo $description; ?></div>
					
				<?php } ?>
				
				<br class=clear>
			</div>
			
		<?php
		
	}	

	
	// radioimage => name, title, type, value, option=>array(value, image)
	function print_meta_input_radioimage($args){
	
		extract($args);
		
		?>
		
			<div class="meta-body">
				<div class="meta-title">
					<label><?php _e($title, 'gdl_back_office'); ?></label>
				</div>
				<div class="meta-input">
				
					<?php foreach( $options as $option ){ ?>
					
						<div class='radio-image-wrapper'>
							<label for="<?php echo $option['value']; ?>">
								<img src=<?php echo GOODLAYERS_PATH.$option['image']?> alt=<?php echo $name;?>>
								<div id="check-list"></div>
							</label>
							<input type="radio" name="<?php echo $name; ?>" value="<?php echo $option['value'];?>" <?php 
								
								if($value == $option['value']){
								
									echo 'checked';
									
								}else if($value == '' && $default == $option['value']){
								
									echo 'checked';
									
								}
								
							?> id="<?php echo $option['value']; ?>" class="<?php echo $name; ?>" > 
						</div>
						
					<?php } ?>
					<br class=clear>
				</div>
				<br class=clear>
			</div>
		<?php
	}	
	
	// imagepicker => title, name=>array(num,image,title,caption,link)
	function print_image_picker($args){
	
		extract($args);
		
		?>
		
			<div class="meta-body image-picker-wrapper">
				<div class="meta-input-slider">
					<div class="image-picker" id="image-picker">
						<input type='hidden' class="slider-num" id="slider-num" name='<?php 
						
							echo (isset($name['slider-num']))? $name['slider-num'] . '[]' : '' ; 
						
						?>' value=<?php 
							
							echo empty($value)? 0: $value->childNodes->length;
							
						?> />
						<div class="selected-image" id="selected-image">
							<div id="selected-image-none"></div>
							<ul>
								<li id="default" class="default">
									<div class="selected-image-wrapper">
										<img src="#"/>
										<div class="selected-image-element">
											<div id="edit-image" class="edit-image"></div>
											<div id="unpick-image" class="unpick-image"></div>
											<br class="clear">
										</div>
									</div>
									<input type="hidden" class='slider-image-url' id='<?php echo $name['image']; ?>' />
									<div id="slider-detail-wrapper" class="slider-detail-wrapper">
									<div id="slider-detail" class="slider-detail"> 	
										<div class="meta-title meta-detail-title"><?php _e('SLIDER TITLE', 'gdl_back_office'); ?></div> 
										<div class="meta-detail-input meta-input"><input type="text" id='<?php echo $name['title']; ?>' /></div><br class="clear">
										<hr class="separator">
										<div class="meta-title meta-detail-title"><?php _e('SLIDER CAPTION', 'gdl_back_office'); ?></div>
										<div class="meta-detail-input meta-input"><textarea id='<?php echo $name['caption']; ?>' ></textarea></div><br class="clear">
										<hr class="separator">
										<div class="meta-title meta-detail-title"><?php _e('LINK TYPE', 'gdl_back_office'); ?></div> 
										<div class="meta-input meta-detail-input">
											<div class="combobox">
												<select id='<?php echo $name['linktype']; ?>'>
													<option selected >No Link</option>
													<option>Lightbox</option>
													<option>Link to URL</option>	
													<option>Link to Video</option>
												</select>
											</div>
											<div class="meta-title meta-detail-title ml0 mt5" rel="url"><?php _e('URL PATH', 'gdl_back_office'); ?></div> 
											<div class="meta-title meta-detail-title ml0 mt5" rel="video"><?php _e('VIDEO PATH (ONLY FOR ANYTHING SLIDER)', 'gdl_back_office'); ?></div> 
											<div><input class="mt10" type="text"  id='<?php echo $name['link']; ?>' /></div>
										</div>
										<br class="clear">
										<div class="meta-detail-done-wrapper">
											<input type="button" id="gdl-detail-edit-done" class="gdl-button" value="Done" /><br class="clear">
										</div>
									</div>
									</div>
								</li>
								
								<?php 
								
									if(!empty($value)){
										
										foreach ($value->childNodes as $slider){ ?> 
										
											<li class="slider-image-init">
												<div class="selected-image-wrapper">
													<img src="<?php 
													
														$thumb_src_preview = wp_get_attachment_image_src( find_xml_value($slider, 'image'), '160x110');
														echo $thumb_src_preview[0]; 
														
													?>"/>
													<div class="selected-image-element">
														<div id="edit-image" class="edit-image"></div>
														<div id="unpick-image" class="unpick-image"></div>
														<br class="clear">
													</div>
												</div>
												<input type="hidden" class='slider-image-url' name='<?php echo $name['image']; ?>[]' id='<?php echo $name['image']; ?>[]' value="<?php echo find_xml_value($slider, 'image'); ?>" /> 
												<div id="slider-detail-wrapper" class="slider-detail-wrapper">
												<div id="slider-detail" class="slider-detail">								
													<div class="meta-title meta-detail-title"><?php _e('SLIDER TITLE', 'gdl_back_office'); ?></div> 
													<div class="meta-detail-input meta-input"><input type="text" name='<?php echo $name['title']; ?>[]' id='<?php echo $name['title']; ?>[]' value="<?php echo find_xml_value($slider, 'title'); ?>" /></div><br class="clear">
													<hr class="separator">
													<div class="meta-title meta-detail-title"><?php _e('SLIDER CAPTION', 'gdl_back_office'); ?></div>
													<div class="meta-detail-input meta-input"><textarea name='<?php echo $name['caption']; ?>[]' id='<?php echo $name['caption']; ?>[]' ><?php echo find_xml_value($slider, 'caption'); ?></textarea></div><br class="clear">
													<hr class="separator">
													<div class="meta-title meta-detail-title"><?php _e('LINK TYPE', 'gdl_back_office'); ?></div>
													<div class="meta-input meta-detail-input">
														<div class="combobox">
															<?php $linktype_val =  find_xml_value($slider, 'linktype'); ?>
															<select name='<?php echo $name['linktype']; ?>[]' id='<?php echo $name['linktype']; ?>' >
																<option <?php echo ($linktype_val == 'No Link')? "selected" : ''; ?> >No Link</option>
																<option <?php echo ($linktype_val == 'Lightbox')? "selected" : ''; ?>>Lightbox</option>
																<option <?php echo ($linktype_val == 'Link to URL')? "selected" : ''; ?>>Link to URL</option>
																<option <?php echo ($linktype_val == 'Link to Video')?  "selected" : ''; ?>>Link to Video</option>
															</select>
														</div>
														<div class="meta-title meta-detail-title ml0 mt5" rel="url"><?php _e('URL PATH', 'gdl_back_office'); ?></div> 
														<div class="meta-title meta-detail-title ml0 mt5" rel="video"><?php _e('VIDEO PATH (ONLY FOR ANYTHING SLIDER)', 'gdl_back_office'); ?></div> 
														<div><input class="mt10" type="text" name='<?php echo $name['link']; ?>[]' id='<?php echo $name['link']; ?>[]' value="<?php echo find_xml_value($slider, 'link'); ?>" /></div>
													</div>
													<br class="clear">
													<div class="meta-detail-done-wrapper">
														<input type="button" id="gdl-detail-edit-done" class="gdl-button" value="Done" /><br class="clear">
													</div>
												</div>
												</div>
												</li> 
												
											<?php
											
										}
										
									}
									
								?>	
								
							</ul>
							<br class=clear>
							<div id="show-media" class="show-media">
								<span id="show-media-text"></span>
								<div id="show-media-image"></div>
							</div>
						</div>
						<div class="media-image-gallery-wrapper">
							<div class="media-image-gallery" id="media-image-gallery">
								<?php get_media_image(); ?>
							</div>
						</div>
					</div>
				</div>
				<br class=clear>
			</div>
			
		<?php
		
	}
	
	// open => id
	function print_meta_open_div($args){
	
		extract($args);
		
		?>
		
			<div id="<?php echo $id; ?>" class="<?php echo $id; ?>" >
		
		<?php
		
	}
	
	// close
	function print_meta_close_div($args){
	
		?>
		
			</div>
			
		<?php
		
	}
*/
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