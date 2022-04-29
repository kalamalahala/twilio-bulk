(function ($) {
	"use strict";
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
});(jQuery);

jQuery(document).on("change", "#twilio-campaign-message",  function() {
	// Get the message body from AJAX
	console.log(jQuery("#twilio-campaign-message").val());
	var message_id = jQuery(this).val();
	var data = {
		action: 'twilio_bulk',
		twilio_bulk_action: 'get_programmable_message',
		programmable_message_id: message_id,
		nonce: twilio_bulk_ajax.nonce
	};

	/* Send the AJAX request
	*  Add the information to the div, and show the div
	*/
	jQuery.post(twilio_bulk_ajax.ajaxurl, data, function(response) {
		response = JSON.parse(response);
		jQuery("#twilio-programmable-message-name").text(response[0].programmable_message_name);
		jQuery("#twilio-programmable-message-description").text(response[0].programmable_message_description);
		jQuery("#twilio-programmable-message-body").text(response[0].programmable_message_content);
		jQuery("#twilio-campaign-message-information").removeClass('d-none');
	});
});

jQuery(document).on("change", "#twilio-file-upload",  function() {
	// Send file to php spreadsheet handler, return the response
	var file = jQuery(this).prop('files')[0];
	var formData = new FormData();
	formData.append('action', 'twilio_bulk');
	formData.append('twilio_bulk_action', 'spreadsheet_handler');
	formData.append('file', file);
	formData.append('nonce', twilio_bulk_ajax.nonce);
	jQuery.ajax({
		url: twilio_bulk_ajax.ajaxurl,
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		success: function(response) {
			response = JSON.parse(response);
			console.log(response);
		},
		error: function(response) {
			console.log(response);
		}
	});
});