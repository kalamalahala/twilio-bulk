jQuery(document).ready(function() {
    jQuery("#contacts-upload").prop("disabled", true);


    // Ajax variables: twilio_bulk_ajax.ajaxurl, twilio_bulk_ajax.nonce
    jQuery("#file").on("change", function() {
        var file = jQuery(this).prop("files")[0];
        var formData = new FormData();
        formData.append("file", file);
        formData.append("action", "twilio_bulk");
        formData.append("twilio_bulk_action", "spreadsheet_initial_upload");
        formData.append("nonce", twilio_bulk_ajax.nonce);
        jQuery.ajax({
            url: twilio_bulk_ajax.ajaxurl,
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                // Create select option groups for required columns as form-group row for First Name, Surname, Email, and Phone Number.
                var key_list = data.keys;
                jQuery("#upload-response-fields").append(
                    "<p class='lead'>" + data.rows + " rows in spreadsheet.</p><div class='form-group row'>" +
                    "<label for='contact_first_name' class='col-sm-2 col-form-label'>" +
                    "Contact Name" +
                    "</label> " +
                    "<div class='col-sm-10'> " +
                    "<select class='form-control' id='contact_first_name'><option value=''>Select a column</option> " );                   
                for (var i = 0; i < key_list.length; i++) {
                    var key = key_list[i];
                    jQuery("#contact_first_name").append(
                        "<option value='" + key + "'>" + key + "</option>"
                    );
                }
                jQuery("#upload-response-fields").append(
                    "</select> " +
                    "</div> " +
                    "</div> "); // End of form-group row for First Name.

                    // Create radio button Yes/No to enable Surname field
                    jQuery("#upload-response-fields").append(
                        "<div class='form-group row'>" +
                        "<label for='contact_surname_yes_no' class='col-sm-2 col-form-label'>" +
                        "Surname Field?" +
                        "</label> " +
                        "<div class='col-sm-10'> " +
                        "<div class='form-check form-check-inline'>" +
                        "<input class='form-check-input' type='radio' name='contact_surname_yes_no' id='contact_surname_yes' value='yes'>" +
                        "<label class='form-check-label' for='contact_surname_yes'>Yes</label>" +
                        "</div> " +
                        "<div class='form-check form-check-inline'>" +
                        "<input class='form-check-input' type='radio' name='contact_surname_yes_no' id='contact_surname_no' value='no'>" +
                        "<label class='form-check-label' for='contact_surname_no'>No</label>" +
                        "</div> " +
                        "</div> " +
                        "</div> "); // End of form-group row for Surname.


                    // Create select option groups for required columns as form-group row for Surname.
                jQuery("#upload-response-fields").append(
                    "<div class='form-group row d-none' id='contact_surname_form_group'>" +
                    "<label for='contact_surname' class='col-sm-2 col-form-label'>" +
                    "Contact Surname" +
                    "</label> " +
                    "<div class='col-sm-10'> " +
                    "<select class='form-control' id='contact_surname'><option value=''>Select a column</option>");
                for (var i = 0; i < key_list.length; i++) {
                    var key = key_list[i];
                    jQuery("#contact_surname").append(
                        "<option value='" + key + "'>" + key + "</option>"
                    );
                }
                jQuery("#upload-response-fields").append(
                    "</select> " +
                    "</div> " +
                    "</div> "); // End of form-group row for Surname.

                    // Create select option groups for required columns as form-group row for Email.
                jQuery("#upload-response-fields").append(
                    "<div class='form-group row' id='contact_email'>" +
                    "<label for='contact_email' class='col-sm-2 col-form-label'>" +
                    "Contact Email" +
                    "</label> " +
                    "<div class='col-sm-10'> " +
                    "<select class='form-control' id='contact_email_field'><option value=''>Select a column</option>");
                for (var i = 0; i < key_list.length; i++) {
                    var key = key_list[i];
                    jQuery("#contact_email_field").append(
                        "<option value='" + key + "'>" + key + "</option>"
                    );
                }
                jQuery("#upload-response-fields").append(
                    "</select> " +
                    "</div> " +
                    "</div> "); // End of form-group row for Email.

                // Create select option groups for required columns as form-group row for Phone Number.
                jQuery("#upload-response-fields").append(
                    "<div class='form-group row' id='contact_phone_number'>" +
                    "<label for='contact_phone_number' class='col-sm-2 col-form-label'>" +
                    "Contact Phone Number" +
                    "</label> " +
                    "<div class='col-sm-10'> " +
                    "<select class='form-control' id='contact_phone_number_field'><option value=''>Select a column</option>");
                for (var i = 0; i < key_list.length; i++) {
                    var key = key_list[i];
                    jQuery("#contact_phone_number_field").append(
                        "<option value='" + key + "'>" + key + "</option>"
                    );
                }
                jQuery("#upload-response-fields").append(
                    "</select> " +
                    "</div> " +
                    "</div> "); // End of form-group row for Phone Number.

                
                


                
                console.log(key_list.length);
                jQuery("#contacts-upload").prop("disabled", false);

            },
            error: function(data) {
                console.log(data);
            },
            complete: function(data) {
                jQuery("#upload-response").removeClass('d-none');
            }
        });











    });
});

// Show/hide Surname field
jQuery(document).on("click", "#contact_surname_yes", function() {
    jQuery("#contact_surname_form_group").removeClass('d-none');
});
jQuery(document).on("click", "#contact_surname_no", function() {
    jQuery("#contact_surname_form_group").addClass('d-none');
});

// Submit AJAX
jQuery(document).on("click", "#contacts-upload", function(submit) {
    submit.preventDefault();
    var form_data = new FormData();
    var file = jQuery("#contact_file")[0].files[0];
    form_data.append("file", file);
    form_data.append("action", "twilio_bulk");
    form_data.append("twilio_bulk_action", "upload_contact_list");
    form_data.append("nonce", twilio_bulk_ajax.nonce);
    jQuery.ajax({
        url: twilio_bulk_ajax.ajaxurl,
        type: "POST",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            jQuery(".bootstrap-wrapper").jQuery(".jumbotron").append("<div class='alert alert-success'>" + data + "</div>");
        },
        error: function(data) {
            console.log(data);
        },
        complete: function(data) {
            jQuery("#upload-response").removeClass('d-none');
        }
    });
});