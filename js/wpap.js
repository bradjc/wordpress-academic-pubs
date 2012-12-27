
jQuery(document).ready(function($) {
	var _orig_send_attachment = wp.media.editor.send.attachment;

	$('input:button.upload_image_button_meta').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;

		upload_text   = jQuery(this).siblings("#upload_image_text_meta");
		attachment_id = jQuery(this).siblings("#upload_image_attachment_id");

		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment) {
			upload_text.val(attachment.url);
			attachment_id.val(attachment.id);
		}
		
		wp.media.editor.open($(this));
		return false;
	});

});
