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
  // Get the programmable message body from AJAX
  var message_id = jQuery(this).val();
  var data = {
    action: "twilio_bulk",
    twilio_bulk_action: "get_programmable_messages",
    programmable_message_id: message_id,
    nonce: twilio_bulk_ajax.nonce,
  };

  jQuery.ajax({
    url: twilio_bulk_ajax.ajaxurl,
    type: "POST",
    data: data,
    success: function (response) {
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
    },
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
      twilio_bulk_action: "get_programmable_messages",
      programmable_message_id: message_id,
      nonce: twilio_bulk_ajax.nonce,
    };

    /* Send the AJAX request

    *  Add the information to the div, and show the div
    */
    jQuery.post(twilio_bulk_ajax.ajaxurl, data, function (response) {
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
  formData.append("twilio_bulk_action", "spreadsheet_initial_upload");
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
      // response = JSON.parse(response);
      // let json = response.json;

      // #twilio-programmable-upload-rows
      jQuery("#twilio-campaign-upload-rows").empty().text(response.rows);
      // #twilio-campaign-upload-keys
      // Print list of keys from payload
      jQuery("#twilio-campaign-upload-keys").empty();
      for (var key in response.keys) {
        jQuery("#twilio-campaign-upload-keys").append(
          "<li>" + response.keys[key] + "</li>"
        );
      }

      // Create a select input for each key for "Select Name Column" in a new row and form group
      jQuery("#twilio-campaign-upload-information").append(
        "<div class='form-group row'><div class='col-sm-2 col-form-label'><label for='twilio-campaign-name-column' class='form-label col-form-label'>Select Name Column</label></div><div class='input-group col-sm-10'><select class='form-control' id='twilio-campaign-name-column'>"
      );
      jQuery("#twilio-campaign-name-column").append(
        "<option value=''>Select Name Column</option>"
      );
      // Add options to select input: index as value, key as text
      for (var key in response.keys) {
        jQuery("#twilio-campaign-name-column").append(
          "<option value='" + key + "'>" + response.keys[key] + "</option>"
        );
      }

      // for (var key in response.keys) {
      //   jQuery("#twilio-campaign-name-column").append(
      //     "<option value='" +
      //       response.keys[key] +
      //       "'>" +
      //       response.keys[key] +
      //       "</option>"
      //   );
      // }
      jQuery("#twilio-campaign-upload-information").append(
        "</select></div></div>"
      );

      // Create a select input with they keys as options in a new row and form group
      jQuery("#twilio-campaign-upload-information").append(
        "<div class='form-group row'><div class='col-sm-2 col-form-label'><label class='form-label col-form-label' for='twilio-campaign-upload-phone-select'>Phone Number Column</label></div><div class='input-group col-sm-10'><select class='form-control' id='twilio-campaign-upload-phone-select'>"
      );
      jQuery("#twilio-campaign-upload-phone-select").append(
        "<option value=''>Select Primary Phone Number</option>"
      );
      // Add options to select input: index as value, key as text
      for (var key in response.keys) {
        jQuery("#twilio-campaign-upload-phone-select").append(
          "<option value='" + key + "'>" + response.keys[key] + "</option>"
        );
      }
      // for (var key in response.keys) {
      //   jQuery("#twilio-campaign-upload-phone-select").append(
      //     "<option value='" +
      //       response.keys[key] +
      //       "'>" +
      //       response.keys[key] +
      //       "</option>"
      //   );
      // }
      jQuery("#twilio-campaign-upload-information").append(
        "</select></div></div>"
      );

      // Create a select input with keys as options for "Select Email Address"
      jQuery("#twilio-campaign-upload-information").append(
        "<div class='form-group row'><div class='col-sm-2 col-form-label'><label class='form-label col-form-label' for='twilio-campaign-upload-email-select'>Email Address Column</label></div><div class='input-group col-sm-10'><select class='form-control' id='twilio-campaign-upload-email-select'>"
      );
      jQuery("#twilio-campaign-upload-email-select").append(
        "<option value=''>Select Email Address</option>"
      );
      // Add options to select input: index as value, key as text
      for (var key in response.keys) {
        jQuery("#twilio-campaign-upload-email-select").append(
          "<option value='" + key + "'>" + response.keys[key] + "</option>"
        );
      }
      
      // for (var key in response.keys) {
      //   jQuery("#twilio-campaign-upload-email-select").append(
      //     "<option value='" +
      //       response.keys[key] +
      //       "'>" +
      //       response.keys[key] +
      //       "</option>"
      //   );
      // }
      jQuery("#twilio-campaign-upload-information").append(
        "</select></div></div>"
      );

      // Append a Yes/No radio button group in a new row and form group with the label "Two name columns?"
      jQuery("#twilio-campaign-upload-information").append(
        "<div class='row form-group'><div class='col-sm-2'><label for='twilio-campaign-two-name-columns'>Two name columns?</label></div><div class='form-check col-sm-1'><input class='form-check-input' type='radio' name='twilio-campaign-two-name-columns' id='twilio-campaign-two-name-columns-yes' value='yes'><label class='form-check-label' for='twilio-campaign-two-name-columns-yes'>Yes</label></div><div class='form-check'><input class='form-check-input' type='radio' name='twilio-campaign-two-name-columns' id='twilio-campaign-two-name-columns-no' value='no' checked><label class='form-check-label' for='twilio-campaign-two-name-columns-no'>No</label></div></div>"
      );

      // If twilio-campaign-two-name-columns-yes is selected once, append a select input for each key for "Select Name Column 2" in a new row and form group
      jQuery("#twilio-campaign-two-name-columns-yes").on('change', function () {
        if (jQuery(this).is(":checked")) {
        jQuery("#twilio-campaign-upload-information").append(
          "<div class='row form-group' id='twilio-campaign-surnames'><div class='col-sm-2 form-label col-form-label'><label for='twilio-campaign-name-column-2'>Select Surname</label></div><div class='col-sm-10'><select class='form-control' id='twilio-campaign-name-column-2'>"
        );
        jQuery("#twilio-campaign-name-column-2").append(
          "<option value=''>Select Surname</option>"
        );
        for (var key in response.keys) {
          jQuery("#twilio-campaign-name-column-2").append(
            "<option value='" +
              response.keys[key] +
              "'>" +
              response.keys[key] +
              "</option>"
          );
        }
        jQuery("#twilio-campaign-name-column-2").append("</select></div></div>");
      }
      });

      // Destroy the select input row for "Select Name Column 2" if twilio-campaign-two-name-columns-no is selected
      jQuery("#twilio-campaign-two-name-columns-no").click(function () {
        jQuery("#twilio-campaign-surnames").remove();
      });

      jQuery("#twilio-campaign-upload-information").removeClass("d-none");
    },
    error: function (response) {
      console.log(response);
    },
  });
});

// submit when #twilio-bulk-submit is clicked
// Send all filledout form data to the server #twilio-file-upload file field
jQuery(document).ready(function () {
  jQuery("#twilio-bulk-submit").click( function () {
    jQuery("#twilio-bulk-submit").prop("disabled", true);
    var formData = new FormData();
    formData.append('file', jQuery('#twilio-file-upload')[0].files[0]);
    formData.append('name_column', jQuery('#twilio-campaign-name-column').val());
    formData.append('phone_column', jQuery('#twilio-campaign-upload-phone-select').val());
    formData.append('email_column', jQuery('#twilio-campaign-upload-email-select').val());
    formData.append('two_name_columns', jQuery('input[name=twilio-campaign-two-name-columns]:checked').val());
    formData.append('name_column_2', jQuery('#twilio-campaign-name-column-2').val());
    formData.append('nonce', twilio_bulk_ajax.nonce);
    formData.append('action', 'twilio_bulk');
    formData.append('twilio_bulk_action', 'campaign_submit');
    // formData.append('_token', jQuery('#csrf-token').val());

    // Begin the ajax request
    jQuery.ajax({
      url: twilio_bulk_ajax.ajaxurl,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        console.log(response);
        jQuery("#twilio-campaign-upload-information").removeClass("d-none");
        jQuery("#twilio-campaign-upload-information").append(
          "<div class='row'><div class='col-sm-12'><div class='alert alert-success' role='alert'>Success!</div></div></div>"
        );
        jQuery("#twilio-bulk-submit").prop("disabled", false);
      },
      error: function (response) {
        console.log(response);
      }
    });
  });
});


// Toggle visibility of #twilio-campaign-follow-up-message and #twilio-campaign-follow-up-time based on #twilio-campaign-follow-up-yes or #twilio-campaign-follow-up-no
jQuery(document).ready(
  function () {
    jQuery("#twilio-campaign-follow-up-yes").click(function () {
      jQuery("#twilio-campaign-follow-up-message").removeClass("d-none");
      jQuery("#twilio-campaign-follow-up-time").removeClass("d-none");
    });
    jQuery("#twilio-campaign-follow-up-no").click(function () {
      jQuery("#twilio-campaign-follow-up-message").addClass("d-none");
      jQuery("#twilio-campaign-follow-up-time").addClass("d-none");
    });
  } /* end of #twilio-campaign-follow-up-yes click */
);
