<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/kalamalahala/
 * @since      1.0.0
 *
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/admin/partials
 */

    use Twilio\Rest\Client;
    $sid = (get_option('twilio_account_sid')) ? get_option('twilio_account_sid') : '';
    $token = (get_option('twilio_account_auth_token')) ? get_option('twilio_account_auth_token') : '';
    $sending_number_selected = (get_option('twilio_account_sending_number')) ? get_option('twilio_account_sending_number') : '';

    // If the tokens are set, ask for existing phone numbers
    if (!empty($sid) && !empty($token)) {
        $twilio = new Client($sid, $token);
        $twilio_bulk_phone_numbers = $twilio->incomingPhoneNumbers->read();

        // Assemble a Select Option group with each number to echo later if the Sending Number is not set in Options
        $sending_number_form_html = '<select name="twilio_bulk_select_sending_number" id="twilio_bulk_select_sending_number" class="form-control">';
        foreach ($twilio_bulk_phone_numbers as $sending_number) {
            $sending_number_form_html .= '<option value="' . $sending_number->phoneNumber . '" ' . (($sending_number->phoneNumber == $sending_number_selected) ? 'selected' : 'logic fail') . ' >' . $sending_number->phoneNumber . ': ' . $sending_number->friendlyName . '</option>';
        }
        $sending_number_form_html .= '</select>';
    }

    // If $_POST['twilio-bulk-option-submit'] is set, update the options if they have changed from their set values
    if (isset($_POST['twilio-bulk-option-submit']) && current_user_can('manage_options')) {
        $submit_msg = '';
        // Update the Twilio Account SID
        if (isset($_POST['twilio-api-sid'])) {
            update_option('twilio_account_sid', sanitize_text_field($_POST['twilio-api-sid']));
            $submit_msg .= '<div class="notice notice-success is-dismissible"><p>Twilio Account SID Updated</p></div>';
        }
        // Update the Twilio Account Auth Token
        if (isset($_POST['twilio-api-token'])) {
            update_option('twilio_account_auth_token', sanitize_text_field($_POST['twilio-api-token']));
            $submit_msg .= '<div class="notice notice-success is-dismissible"><p>Twilio Account Auth Token Updated</p></div>';
        }
        // Update the Twilio Account Sending Number
        if (isset($_POST['twilio-api-sending-number'])) {
            update_option('twilio_account_sending_number', sanitize_text_field($_POST['twilio-api-sending-number']));
            $submit_msg .= '<div class="notice notice-success is-dismissible"><p>Twilio Account Sending Number Updated</p></div>';
        }
    } else {
        // If the user is not an admin, do not allow them to change the options
        $submit_msg = '<div class="notice notice-error is-dismissible"><p>You do not have permission to change these options</p></div>';
    }
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->



<div class="bootstrap-wrapper">
    <div class="jumbotron ">
        <h1>Twilio Bulk Messaging</h1>
        <p>Send Bulk SMS to your contacts</p>
    </div>
    <div class="shadow p-4 mb-4 bg-white col-sm-5">
        <h2>Plugin Options</h2>

        <form action="admin.php?page=twilio-bulk-dashboard" enctype="multipart/form-data" method="POST" autocomplete="new-password">
            <h4>Twilio API Credentials</h4>
            <p>Access your Twilio account and get your credentials at your <a href="https://console.twilio.com/" target="_blank" title="Twilio Console">Twilio Console</a></p>
            <div class="form-group row">
                <label for="twilio-api-sid" class="col-sm-2 col-form-label" aria-describedby="twilio-api-sid-tip">Account SID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="twilio-api-sid" name="twilio-api-sid" placeholder="Twilio API SID" required value="<?php echo esc_attr(get_option('twilio_account_sid')); ?>">
                    <p id="twilio-api-sid-tip" class="form-text text-muted mb-0">Your user account's primary API key available in the Console.</small>
                </div>
            </div>
            <div class="form-group row">
                <label for="twilio-api-token" class="col-sm-2 col-form-label">Auth Token</label>
                <div class="col-sm-10">
                    <input type="password" autocomplete="new-password" class="form-control" id="twilio-api-token" name="twilio-api-token" placeholder="Twilio API Token" aria-describedby="twilio-token-tip" required value="<?php echo esc_attr(get_option('twilio_account_auth_token')); ?>">
                    <p id="twilio-token-tip" class="form-text text-muted mb-0">This is a private key!</p>
                </div>
            </div>
            <div class="form-group row">
                <label for="twilio-phone-number" class="col-sm-2 col-form-label">Twilio Phone Number</label>
                <div class="col-sm-10">
                    <?php echo (!empty($sending_number_form_html)) ? $sending_number_form_html : '<p>No Twilio Phone Numbers found. Please add a Phone Number to your account.</p>'; ?>
                    <p id="twilio-phone-number-tip" class="form-text text-muted mb-0">Your Twilio phone number. If you don't have one, you can <a href="https://www.twilio.com/console/phone-numbers/incoming" target="_blank" title="Twilio Phone Numbers">create one here</a>.</p>
                </div>
            </div>
            <?php
             // add a row for $submit_msg if it isn't empty
                if (!empty($submit_msg)) {
                    echo '<div class="form-group row">';
                    echo $submit_msg;
                    echo '</div>';
                }
            ?>
            <div class="form-group row">

                <button name="twilio-bulk-option-submit" type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;
                <button name="twilio-bulk-option-reset" type="reset" class="btn btn-secondary">Reset</button>

            </div>
        </form>
    </div>
</div>

<?php
// echo '<h2>Handle POST contents and update Wordpress Options on form submit</h2>';
// if (isset($_POST['twilio-submit'])) {
//     echo '<p>Form submitted!</p>';
//     echo '<pre>';
//     echo get_option('twilio_account_sending_number');
//     var_dump($_POST);
//     echo '</pre>';
//     $twilio_account_sid = $_POST['twilio-api-sid'];
//     $twilio_account_auth_token = $_POST['twilio-api-token'];
//     $twilio_account_phone_number = $_POST['twilio-phone-number'];
//     $twilio_account_sending_number = $_POST['twilio_bulk_select_sending_number'];
//     // $twilio_account_file_upload = $_FILES['twilio-file-upload'];
//     update_option('twilio_account_sid', $twilio_account_sid);
//     update_option('twilio_account_auth_token', $twilio_account_auth_token);
//     // update_option('twilio_account_phone_number', $twilio_account_phone_number);
//     update_option('twilio_account_sending_number', $twilio_account_sending_number);
//     // update_option('twilio_account_file_upload', $twilio_account_file_upload);
// }

?>