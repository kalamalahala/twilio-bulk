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
});
jQuery;

jQuery(document).on("change", "#twilio-campaign-message", function () {
  // Get the message body from AJAX
  var message_id = jQuery(this).val();
  var data = {
    action: "twilio_bulk",
    twilio_bulk_action: "get_programmable_message",
    programmable_message_id: message_id,
    nonce: twilio_bulk_ajax.nonce,
  };

  /* Send the AJAX request
   *  Add the information to the div, and show the div
   */
  jQuery.post(twilio_bulk_ajax.ajaxurl, data, function (response) {
    response = JSON.parse(response);
    jQuery("#twilio-programmable-message-name").text(
      response[0].programmable_message_name
    );
    jQuery("#twilio-programmable-message-description").text(
      response[0].programmable_message_description
    );
    jQuery("#twilio-programmable-message-body").text(
      response[0].programmable_message_content
    );
    jQuery("#twilio-campaign-message-information").removeClass("d-none");
  });
});

// repeat above for #twilio-campaign-follow-up-message-information
jQuery(document).on(
  "change",
  "#twilio-campaign-follow-up-message",
  function () {
    // Get the message body from AJAX
    var message_id = jQuery(this).val();
    var data = {
      action: "twilio_bulk",
      twilio_bulk_action: "get_programmable_message",
      programmable_message_id: message_id,
      nonce: twilio_bulk_ajax.nonce,
    };

    /* Send the AJAX request

    *  Add the information to the div, and show the div
    */
    jQuery.post(twilio_bulk_ajax.ajaxurl, data, function (response) {
      response = JSON.parse(response);
      jQuery("#twilio-programmable-follow-up-message-name").text(
        response[0].programmable_message_name
      );
      jQuery("#twilio-programmable-follow-up-message-description").text(
        response[0].programmable_message_description
      );
      jQuery("#twilio-programmable-follow-up-message-body").text(
        response[0].programmable_message_content
      );
      jQuery("#twilio-campaign-follow-up-message-information").removeClass(
        "d-none"
      );
    });
  }
);

jQuery(document).on("change", "#twilio-file-upload", function () {
  // Send file to php spreadsheet handler, return the response
  var file = jQuery(this).prop("files")[0];
  var formData = new FormData();
  formData.append("action", "twilio_bulk");
  formData.append("twilio_bulk_action", "spreadsheet_handler");
  formData.append("file", file);
  formData.append("nonce", twilio_bulk_ajax.nonce);
  jQuery.ajax({
    url: twilio_bulk_ajax.ajaxurl,
    type: "POST",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      response = JSON.parse(response);
      let json = response.json;

      // #twilio-programmable-upload-rows
      jQuery("#twilio-campaign-upload-rows").empty().text(json.rows);
      // #twilio-campaign-upload-keys
      // Print list of keys from payload
      jQuery("#twilio-campaign-upload-keys").empty();
      for (var key in json.keys) {
        jQuery("#twilio-campaign-upload-keys").append(
          "<li>" + json.keys[key] + "</li>"
        );
      }
      // Create a select input with they keys as options in a new row and form group
      jQuery("#twilio-campaign-upload-information").append(
        "<div class='form-group row'><div class='col-sm-2 col-form-label'><label class='form-label col-form-label' for='twilio-campaign-upload-key-select'>Phone Number Column</label></div><div class='input-group col-sm-10'><select class='form-control' id='twilio-campaign-upload-key-select'></select></div></div>"
      );
      jQuery("#twilio-campaign-upload-key-select").append(
        "<option value=''>Select Primary Phone Number</option>"
      );
      for (var key in json.keys) {
        jQuery("#twilio-campaign-upload-key-select").append(
          "<option value='" +
            json.keys[key] +
            "'>" +
            json.keys[key] +
            "</option>"
        );
      }

      jQuery("#twilio-campaign-upload-information").removeClass("d-none");
    },
    error: function (response) {
      console.log(response);
    },
  });
});

// if #twilio-campaign-follow-up-no is checked, hide #twilio-campaign-follow-up-time, #twilio-campaign-follow-up-message
// remove required from #twilio-campaign-follow-up-time and #twilio-campaign-follow-up-time-units
// jQuery(document).on("click", "#twilio-campaign-follow-up-no", function () {
//     jQuery("#twilio-campaign-follow-up-time").addClass("d-none");
//     jQuery("#twilio-campaign-follow-up-time-units").addClass("d-none");
//     jQuery("#twilio-campaign-follow-up-time").attr("required", false);
//     jQuery("#twilio-campaign-follow-up-time-units").attr("required", false);
//     jQuery("#twilio-campaign-follow-up-message").attr("required", false);
//     jQuery("#twilio-campaign-follow-up-message").addClass("d-none");
// } /* end of #twilio-campaign-follow-up-no change */);

// // reverse if #twilio-campaign-follow-up-yes is checked
// jQuery(document).on("click", "#twilio-campaign-follow-up-yes", function () {
//   jQuery("#twilio-campaign-follow-up-time").removeClass("d-none");
//   jQuery("#twilio-campaign-follow-up-time-units").removeClass("d-none");
//   jQuery("#twilio-campaign-follow-up-time").attr("required", true);
//   jQuery("#twilio-campaign-follow-up-time-units").attr("required", true);
//   jQuery("#twilio-campaign-follow-up-message").attr("required", true);
//   jQuery("#twilio-campaign-follow-up-message").removeClass("d-none");
// }
// );

// submit when #twilio-bulk-submit is clicked
jQuery(document).ready(function () {
  jQuery('#twilio-bulk-submit').click(function (e) {
    e.preventDefault();
    var form = jQuery('#twilio-bulk-form');
    var formData = new FormData(form[0]);
    formData.append("action", "twilio_bulk");
    formData.append("twilio_bulk_action", "campaign_submit");
    formData.append("nonce", twilio_bulk_ajax.nonce);
    jQuery.ajax({
      url: twilio_bulk_ajax.ajaxurl,
      type: "POST",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        response = JSON.parse(response);
        jQuery("#twilio-bulk-response-message").empty().text(response.message);
        // #twilio-bulk-response-container remove class d-none
        jQuery("#twilio-bulk-response-container").removeClass("d-none");
      },
      error: function (response) {
        console.log(response);
      },
    });
  } /* end of #twilio-bulk-submit click */);
});
