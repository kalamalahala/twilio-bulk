<?php

/**
 * Create New Campaign Upload Form
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/kalamalahala/
 * @since      1.0.0
 *
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/admin/partials
 */

use twilio_bulk\TwilioBulkAjax;

// Collect programmed messages for form data
// $messages = $this->get_programmable_messages();

$AJAX_request = new TwilioBulkAjax( 'none', 'twilio_bulk_ajax', 'get_programmable_messages', null, 'twilio_bulk_ajax_nonce' );
// wp_die('Where is -1 getting returned from?');
$messages = $AJAX_request->get_programmable_messages(0, false);
$decoded_messages = json_decode($messages, false);
?>

<div class="bootstrap-wrapper">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js" integrity="sha512-x/vqovXY/Q4b+rNjgiheBsA/vbWA3IVvsS8lkQSX1gQ4ggSJx38oI2vREZXpTzhAv6tNUaX81E7QBBzkpDQayA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" /> -->
    <div class="jumbotron ">
        <h1>Create New Campaign</h1>
        <p>Upload a list of contacts, choose your settings, and send your message!</p>
    </div>
    <div class="shadow p-4 mb-4 bg-white col-sm-5">
        <h2 id="campaign-header">Campaign Details</h2>

        <form action="" enctype="multipart/form-data" method="POST" autocomplete="new-password">

            <p>Enter a name for your campaign and upload a list of contacts.</p>
            <div class="form-group row">
                <label for="twilio-campaign-name" class="col-sm-2 col-form-label" aria-describedby="twilio-campaign-name-tip">Campaign Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="twilio-campaign-name" name="twilio-campaign-name" placeholder="Campaign Name" required>
                    <p id="twilio-campaign-name-tip" class="form-text text-muted mb-0">Enter a name for your campaign.</p>
                </div>
            </div>
            <div class="form-group row">
                <label for="twilio-file-upload" class="form-label col-2">Upload Contact List</label>
                <div class="input-group col-3">
                    <input id="twilio-file-upload" name="twilio-file-upload" type="file" class="form-control-file" accept=".xls,.xlsx,.csv">
                </div>
            </div>
            <div class="container-sm d-none" id="twilio-campaign-upload-information">
                <div class="row">
                    <div class="col-sm-12 success-message alert alert-success">
                        <h4>Upload Contents</h4>
                        <div class="row">
                            <label for="twilio-campaign-upload-rows" class="col-sm-4"><strong>Rows In File</strong></label>
                            <p id="twilio-campaign-upload-rows" class="col-sm-8">Number of rows in the file should display here</p>
                        </div>
                        <div class="row">
                            <label for="twilio-campaign-upload-keys" class="col-sm-4"><strong>List of Columns</strong></label>
                            <p id="twilio-campaign-upload-keys" class="col-sm-8">List of columns in the file should display here</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="twilio-campaign-message" class="col-sm-2 col-form-label" aria-describedby="twilio-campaign-message-tip">Campaign Message</label>
                <div class="col-sm-10">
                    <select class="form-control" id="twilio-campaign-message" name="twilio-campaign-message" placeholder="Campaign Message" required>
                        <option value="">Please select a message</option>
                        <?php /* Dropdown box of pre-made messages. Need a database table for automated outgoing messages. */

                        foreach ($decoded_messages as $message) {
                            echo '<option value="' . $message->id . '">' . $message->programmable_message_name . '</option>';
                        }

                        ?>
                    </select>
                    <p id="twilio-campaign-message-tip" class="form-text text-muted mb-2">Select a message for your campaign.</p>

                </div>
            </div>
            <!-- Selected Message Information Template -->
            <div class="container-sm d-none" id="twilio-campaign-message-information">
                <div class="row">
                    <div class="col-sm-8 success-message alert alert-success">
                        <h4>Message Information</h4>
                        <div class="row">
                            <label for="twilio-programmable-message-name" class="col-sm-4"><strong>Message Name</strong></label>
                            <p id="twilio-programmable-message-name" class="col-sm-8">Programmable Message Name</p>
                        </div>
                        <div class="row">
                            <label for="twilio-programmable-message-description" class="col-sm-4"><strong>Description</strong></label>
                            <p id="twilio-programmable-message-description" class="col-sm-8">Programmable Message Description</p>
                        </div>
                        <div class="row">
                            <label for="twilio-programmable-message-body" class="col-sm-4"><strong>Body</strong></label>
                            <p id="twilio-programmable-message-body" class="col-sm-8">Programmable Message Body</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <!-- Use follow up message? Radio Button Yes/No -->
                <label for="twilio-campaign-follow-up" class="col-sm-2 col-form-label" aria-describedby="twilio-campaign-follow-up-tip">Use Follow Up Message?</label>
                <div class="col-sm-10">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="twilio-campaign-follow-up" id="twilio-campaign-follow-up-yes" value="yes" checked>
                        <label class="form-check-label" for="twilio-campaign-follow-up-yes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="twilio-campaign-follow-up" id="twilio-campaign-follow-up-no" value="no">
                        <label class="form-check-label" for="twilio-campaign-follow-up-no">No</label>
                    </div>
                    <p id="twilio-campaign-follow-up-tip" class="form-text text-muted mb-0">Do you want to use a follow up message?</p>
                </div>
            </div>
            <!-- Select Follow Up Message. Apply class d-none if radio #twilio-campaign-follow-up-no No is selected. -->
            <div class="form-group row" id="twilio-campaign-follow-up-message">
                <label for="twilio-campaign-follow-up-message" class="col-sm-2 col-form-label" aria-describedby="twilio-campaign-follow-up-message-tip">Follow Up Message</label>
                <div class="col-sm-10">
                    <select class="form-control" id="twilio-campaign-follow-up-message-select" name="twilio-campaign-follow-up-message" placeholder="Follow Up Message" required>
                        <option value="">Please select a message</option>
                        <?php /* Dropdown box of pre-made messages. Need a database table for automated outgoing messages. */

                        foreach ($decoded_messages as $message) {
                            echo '<option value="' . $message->id . '">' . $message->programmable_message_name . '</option>';
                        }

                        ?>
                    </select>
                    <p id="twilio-campaign-follow-up-message-tip" class="form-text text-muted mb-0">Select a message for your campaign.</p>

                </div>
            </div>
            <!-- Select amount of time to wait before sending follow up message. Apply class d-none if radio #twilio-campaign-follow-up-no No is selected. -->
            <div class="form-group row" id="twilio-campaign-follow-up-time">
                <div class="col-sm-2"><label for="twilio-campaign-follow-up-time" class="col-form-label" aria-describedby="twilio-campaign-follow-up-time-tip">Follow Up Time</label></div>
                <div class="col-sm-10 row">
                    <!-- Number input, range 0-60 -->
                    <input type="number" min="0" max="60" class="form-control col-sm-2" id="twilio-campaign-follow-up-time" name="twilio-campaign-follow-up-time" placeholder="Follow Up Time" required value="0">
                    <!-- Units of time in a Select input: seconds, minutes, hours, days -->
                    <select class="form-control col-sm-6 ml-2" id="twilio-campaign-follow-up-time-units" name="twilio-campaign-follow-up-time-units" placeholder="Follow Up Time Units" required>
                        <option value="seconds">Seconds</option>
                        <option value="minutes">Minutes</option>
                        <option value="hours">Hours</option>
                        <option value="days">Days</option>
                    </select>
                    <p id="twilio-campaign-follow-up-time-tip" class="form-text text-muted mb-0">Select time until follow-up message is sent to contacts that haven't replied.</p>
                </div>
            </div>
            <!-- Selected Follow Up Message Information Template -->
            <div class="container-sm d-none" id="twilio-campaign-follow-up-message-information">
                <div class="row">
                    <div class="col-sm-8 success-message alert alert-success">
                        <h4>Message Information</h4>
                        <div class="row">
                            <label for="twilio-programmable-follow-up-message-name" class="col-sm-4"><strong>Message Name</strong></label>
                            <p id="twilio-programmable-follow-up-message-name" class="col-sm-8">Programmable Message Name</p>
                        </div>
                        <div class="row">
                            <label for="twilio-programmable-follow-up-message-description" class="col-sm-4"><strong>Description</strong></label>
                            <p id="twilio-programmable-follow-up-message-description" class="col-sm-8">Programmable Message Description</p>
                        </div>
                        <div class="row">
                            <label for="twilio-programmable-follow-up-message-body" class="col-sm-4"><strong>Body</strong></label>
                            <p id="twilio-programmable-follow-up-message-body" class="col-sm-8">Programmable Message Body</p>
                        </div>
                    </div>
                </div>
            </div>




            <div class="form-group row">
                <!-- Send Date datepicker -->
                <label for="twilio-campaign-send-date" class="col-sm-2 col-form-label" aria-describedby="twilio-campaign-send-date-tip">Send Date</label>
                <div class="col-sm-10 input-group date" id="datetimepicker2">
                    <input type="text" class="form-control" id="twilio-campaign-send-date" name="twilio-campaign-send-date" placeholder="" required aria-describedby="twilio-campaign-send-date-tip" />
                    <div class="input-group-append" data-target="twilio-campaign-send-date" data-toggle="datetimepicker2">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <div id="twilio-campaign-send-date-tip" class="form-text text-muted mb-0 col-sm-12">Select a date for your campaign to send.</div>
                </div>
            </div>

            <script type="text/javascript">
                // datetimepicker2
                        jQuery('#twilio-campaign-send-date').datetimepicker({
                            // set datetimepicker options
                            format: 'Y-m-d H:i',
                            datepicker: true,
                            timepicker: true,
                            step: 5
                        });

            </script>

            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="button" class="btn btn-primary" id="twilio-bulk-submit">Create Campaign</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
        <!-- Container #twilio-bulk-response-container, initially d-none, to contain ajax response -->
        <div class="container-sm d-none" id="twilio-bulk-response-container">
            <div class="row">
                <div class="col-sm-8 success-message alert alert-success">
                    <h4>Campaign Created</h4>
                    <p id="twilio-bulk-response-message"></p>
                </div>
            </div>

    </div>
</div>

        <!-- print $_POST contents for debugging -->
        <div class="container-sm" id="twilio-campaign-post-data">
            <div class="row">
                <div class="col-sm-12 success-message alert alert-success">
                    <h4>POST Data</h4>
                    <div class="row">
                        <label for="twilio-campaign-post-data" class="col-sm-4"><strong>POST Data</strong></label>
                        <p id="twilio-campaign-post-data" class="col-sm-8">
                            <?php
                            // if (isset($_POST)) {
                            //     echo '<pre>';
                            //     print_r($_POST);
                            //     echo '</pre>';
                            // }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>