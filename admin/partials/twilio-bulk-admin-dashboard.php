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
                    <input type="text" class="form-control" id="twilio-phone-number" name="twilio-phone-number" placeholder="Twilio Phone Number" aria-describedby="twilio-phone-number-tip" value="<?php echo esc_attr(get_option('twilio_account_phone_number')); ?>">
                    <p id="twilio-phone-number-tip" class="form-text text-muted mb-0">Your Twilio phone number. If you don't have one, you can <a href="https://www.twilio.com/console/phone-numbers/incoming" target="_blank" title="Twilio Phone Numbers">create one here</a>.</p>
                </div>
            </div>



            <!-- Remove d-none to bring file upload back in here -->
            <div class="form-group form-row d-none">
                <label for="twilio-file-upload" class="form-label col-2">Upload Contact List</label>
                <div class="input-group col-3">
                    <input id="twilio-file-upload" name="twilio-file-upload" type="file" class="form-control-file">
                </div>
            </div>
            <!-- Hidden file Upload -->

            <div class="form-group row">

                <button name="twilio-submit" type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;
                <button name="twilio-reset" type="reset" class="btn btn-secondary">Reset</button>

            </div>
        </form>
    </div>
</div>

<?php
// echo '<h2>Handle POST contents and update Wordpress Options on form submit</h2>';
if (isset($_POST['twilio-submit'])) {
    // echo '<p>Form submitted!</p>';
    $twilio_account_sid = $_POST['twilio-api-sid'];
    $twilio_account_auth_token = $_POST['twilio-api-token'];
    $twilio_account_phone_number = $_POST['twilio-phone-number'];
    // $twilio_account_file_upload = $_FILES['twilio-file-upload'];
    update_option('twilio_account_sid', $twilio_account_sid);
    update_option('twilio_account_auth_token', $twilio_account_auth_token);
    update_option('twilio_account_phone_number', $twilio_account_phone_number);
    // update_option('twilio_account_file_upload', $twilio_account_file_upload);
}

?>