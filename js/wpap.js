
jQuery(document).ready(function() {

	jQuery('input:button.upload_image_button_meta').click(function() {
	//	example_image =  jQuery(this).siblings("#meta-input-example-image");
		upload_text = jQuery(this).siblings("#upload_image_text_meta");
		attachment_id = jQuery(this).siblings("#upload_image_attachment_id");
		tb_show('Upload Media', 'media-upload.php?post_id=&type=image&amp;TB_iframe=true');
		window.send_to_editor = function(html){
			image_url = jQuery(html).attr('href');
			thumb_url = jQuery('img',html).attr('src');
			attid = jQuery(html).attr('attid');
			
			upload_text.val(image_url);
			attachment_id.val(attid);
	//		example_image.html('<img src=' + thumb_url + ' />');
			tb_remove();
		}
		return false;
	});
	
});
