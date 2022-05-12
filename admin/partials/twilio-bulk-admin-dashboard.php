<?php

/**
 * Provide a admin area view for the plugin
 *
 * This is the primary dashboard Wordpress Template for setting options, managing the plugin, and viewing numbers at a glance
 *
 * @link       https://github.com/kalamalahala/
 * @since      1.0.0
 *
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/admin/partials
 */

// Handle options updates before rendering page:
if (isset($_POST['twilio-bulk-option-submit']) && current_user_can('manage_options')) {
    $twilio_account_sid = $_POST['twilio-api-sid'];
    $twilio_account_auth_token = $_POST['twilio-api-token'];
    $twilio_account_sending_number = $_POST['twilio-account-sending-number-select'];
    $twilio_account_sending_number_sid = $_POST['twilio-account-sending-number-sid'];
    $twilio_account_sending_number_smsUrl = $_POST['twilio-account-sending-number-smsUrl'];
    $twilio_account_sending_number_smsMethod = $_POST['twilio-account-sending-number-smsMethod'];
    // Update the option if it is changed
    $list_of_updates = '';
    if ($twilio_account_sid != get_option('twilio_account_sid')) {
        update_option('twilio_account_sid', $twilio_account_sid);
        $list_of_updates .= '<div class="notice notice-success is-dismissible"><p>Twilio Account SID updated.</p></div>';
    }
    if ($twilio_account_auth_token != get_option('twilio_account_auth_token')) {
        update_option('twilio_account_auth_token', $twilio_account_auth_token);
        $list_of_updates .= '<div class="notice notice-success is-dismissible"><p>Twilio Account Auth Token updated.</p></div>';
    }
    if ($twilio_account_sending_number != get_option('twilio_account_sending_number')) {
        update_option('twilio_account_sending_number', $twilio_account_sending_number);
        $list_of_updates .= '<div class="notice notice-success is-dismissible"><p>Twilio Account Sending Number updated.</p></div>';
    }
    if ($twilio_account_sending_number_sid != get_option('twilio_account_sending_number_sid')) {
        update_option('twilio_account_sending_number_sid', $twilio_account_sending_number_sid);
        $list_of_updates .= '<div class="notice notice-success is-dismissible"><p>Twilio Account Sending Number SID updated.</p></div>';
    }
    if ($twilio_account_sending_number_smsUrl != get_option('twilio_account_sending_number_smsUrl')) {
        update_option('twilio_account_sending_number_smsUrl', $twilio_account_sending_number_smsUrl);
        $list_of_updates .= '<div class="notice notice-success is-dismissible"><p>Twilio Account Sending Number SMS URL updated.</p></div>';
    }
    if ($twilio_account_sending_number_smsMethod != get_option('twilio_account_sending_number_smsMethod')) {
        update_option('twilio_account_sending_number_smsMethod', $twilio_account_sending_number_smsMethod);
        $list_of_updates .= '<div class="notice notice-success is-dismissible"><p>Twilio Account Sending Number SMS Method updated.</p></div>';
    }
} else if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Import Twilio's SMS API Class
use Twilio\Rest\Client;

// Collect options if they are set
$sid = (get_option('twilio_account_sid')) ? get_option('twilio_account_sid') : '';
$token = (get_option('twilio_account_auth_token')) ? get_option('twilio_account_auth_token') : '';
$chosen_sending_number = (get_option('twilio_account_sending_number')) ? get_option('twilio_account_sending_number') : '';
$chosen_sending_number_sid = (get_option('twilio_account_sending_number_sid')) ? get_option('twilio_account_sending_number_sid') : '';
$chosen_sending_number_smsUrl = (get_option('twilio_account_sending_number_smsUrl')) ? get_option('twilio_account_sending_number_smsUrl') : 'https://console.twilio.com/';
$chosen_sending_number_smsMethod = (get_option('twilio_account_sending_number_smsMethod')) ? get_option('twilio_account_sending_number_smsMethod') : '';


// If the tokens are set, ask for existing phone numbers
if (!empty($sid) && !empty($token)) {
    $twilio = new Client($sid, $token);
    $twilio_bulk_phone_numbers = $twilio->incomingPhoneNumbers->read();
    // If $twilio_bulk_phone_numbers has results, ask for the sending number's SMS SID, SMS URL, and SMS Method for the number that matches the chosen sending number
    if (!empty($twilio_bulk_phone_numbers)) {
        foreach ($twilio_bulk_phone_numbers as $twilio_bulk_phone_number) {
            if ($twilio_bulk_phone_number->phoneNumber == $chosen_sending_number) {
                // Set the variables to current API values
                $smsSid = $twilio_bulk_phone_number->sid;
                $smsUrl = $twilio_bulk_phone_number->smsUrl;
                $smsMethod = $twilio_bulk_phone_number->smsMethod;
            }
        }
    }
	// If $list_of_updates has contents, update the matching sid in the Twilio API
	if (!is_null($_POST) && !empty($list_of_updates)) {
		$twilio->incomingPhoneNumbers($twilio_account_sending_number_sid)->update(
			array(
				'smsUrl' => $twilio_account_sending_number_smsUrl,
				'smsMethod' => $twilio_account_sending_number_smsMethod
			)
		);
	}
}


$incomingPhoneNumbers_readable = '';
// Get all properties of each number object and add to a string
foreach ($twilio_bulk_phone_numbers as $number) {
    $incomingPhoneNumbers_readable .= '<strong>' . $number->friendlyName . ' (' . $number->phoneNumber . ')</strong><br><ul>';
    $incomingPhoneNumbers_readable .= '<li>SID: ' . $number->sid . '</li>';
    $incomingPhoneNumbers_readable .= '<li>smsMethod: ' . $number->smsMethod . '</li>';
    $incomingPhoneNumbers_readable .= '<li>smsUrl: ' . $number->smsUrl . '</li>';
}

// Create a select option group for each number. If $chosen_sending_number is set, select that number.
$available_sending_numbers = '';
if (!empty($twilio_bulk_phone_numbers)) {
    $available_sending_numbers .= '<p>Sending Number set in Options: <strong>' . $chosen_sending_number . '</strong></p>';

    $available_sending_numbers .= '<div class="form-group row"><label for="twilio-account-sending-number-select" class="col-sm-2 col-form-label">Sending Number</label><select class="custom-select col-sm-4" name="twilio-account-sending-number-select" id="twilio-account-sending-number-select">';
    $available_sending_numbers .= (!empty($chosen_sending_number)) ? '<option selected value="' . $chosen_sending_number . '">' . $chosen_sending_number . '</option>' : '';
    foreach ($twilio_bulk_phone_numbers as $number) {
        if ($number->phoneNumber != $chosen_sending_number) {
            $available_sending_numbers .= '<option value="' . $number->phoneNumber . '">' . $number->phoneNumber . '</option>';
        }
    }
    $available_sending_numbers .= '</select></div>';
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
                    <?php echo (!empty($available_sending_numbers)) ? $available_sending_numbers : '<p>No Twilio Phone Numbers found. Please add a Phone Number to your account.</p>'; ?>
                    <p id="twilio-phone-number-tip" class="form-text text-muted mb-0">Your Twilio phone number. If you don't have one, you can <a href="https://www.twilio.com/console/phone-numbers/incoming" target="_blank" title="Twilio Phone Numbers">create one here</a>.</p>
                </div>
            </div>


            <?php
            // If a Sending Number is set, display fields for the URL and Method
            // use these identifiers for the name and id attributes of the form fields:
            /*
                twilio-account-sending-number-sid
                twilio-account-sending-number-smsUrl
                twilio-account-sending-number-smsMethod
            */      
            if (!empty($chosen_sending_number)) {
            ?>
                <!-- Sending Number attribute fields -->
                <div class="form-group row">
                    <!-- Webhook URL -->
                    <label for="twilio-account-sending-number-smsUrl" class="col-sm-2 col-form-label">Webhook URL & Method</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="twilio-account-sending-number-smsUrl" name="twilio-account-sending-number-smsUrl" placeholder="Webhook URL" value="<?php echo esc_attr(get_option('twilio_account_sending_number_smsUrl')); ?>" aria-describedby="twilio-account-sending-number-smsUrl-tip">
                        <p id="twilio-account-sending-number-smsUrl-tip" class="form-text text-muted mb-0">How inbound messages to the plugin are handled.</p>
                    </div>
                    <!-- Webhook Method -->
                    <div class="col-sm-2">
                        <select class="custom-select" name="twilio-account-sending-number-smsMethod" id="twilio-account-sending-number-smsMethod">
                            <option value="GET" <?php if (isset($chosen_sending_number_smsMethod) && $chosen_sending_number_smsMethod == 'GET') { echo 'selected'; } ?>>GET</option>
                            <option value="POST" <?php if (isset($chosen_sending_number_smsMethod) && $chosen_sending_number_smsMethod == 'POST') { echo 'selected'; } ?>>POST</option>
                        </select>
                    <!-- Webhook SID, hidden later -->
                    <input type="hidden" name="twilio-account-sending-number-sid" id="twilio-account-sending-number-sid" value="<?php echo $smsSid; ?>">
                </div>
                <?php
            }
                ?>
                </div>
                <div class="form-group row">
                <?php
                // If updates occured, display the messages here
                if (!empty($list_of_updates)) {
                    echo $list_of_updates;
                }
                ?>
				</div>

                <div class="form-group row">

                    <button name="twilio-bulk-option-submit" type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;
                    <button name="twilio-bulk-option-reset" type="reset" class="btn btn-secondary">Reset</button>

                </div>
        </form>
    </div>

    <!-- Container to output $incomingPhoneNumbers_readable -->
    <div class="shadow p-4 mb-4 bg-white col-sm-5">
        <h2>Available Phone Numbers</h2>
        <?php echo $incomingPhoneNumbers_readable; ?>
    </div>