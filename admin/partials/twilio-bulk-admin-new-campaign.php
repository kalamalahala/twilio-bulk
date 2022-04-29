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

// Collect programmed messages for form data
$messages = $this->get_programmable_messages();
$decoded_messages = json_decode($messages, false);
?>

<div class="bootstrap-wrapper">
    <div class="jumbotron ">
        <h1>Create New Campaign</h1>
        <p>Upload a list of contacts, choose your settings, and send your message!</p>
    </div>
    <div class="shadow p-4 mb-4 bg-white col-sm-5">
        <h2 id="campaign-header">Campaign Details</h2>

        <form action="admin.php?page=twilio-bulk-new-campaign" enctype="multipart/form-data" method="POST" autocomplete="new-password">

            <p>Enter a name for your campaign and upload a list of contacts.</p>
            <div class="form-group row">
                <label for="twilio-campaign-name" class="col-sm-2 col-form-label" aria-describedby="twilio-campaign-name-tip">Campaign Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="twilio-campaign-name" name="twilio-campaign-name" placeholder="Campaign Name" required value="<?php echo esc_attr(get_option('twilio_campaign_name')); ?>">
                    <p id="twilio-campaign-name-tip" class="form-text text-muted mb-0">Enter a name for your campaign.</p>
                </div>
            </div>
            <div class="form-group row">
                <label for="twilio-file-upload" class="form-label col-2">Upload Contact List</label>
                <div class="input-group col-3">
                    <input id="twilio-file-upload" name="twilio-file-upload" type="file" class="form-control-file" accept=".xls,.xlsx,.csv">
                </div>
            </div>
            <div class="form-group row">
                <label for="twilio-campaign-message" class="col-sm-2 col-form-label" aria-describedby="twilio-campaign-message-tip">Campaign Message</label>
                <div class="col-sm-10">
                    <select class="form-control" id="twilio-campaign-message" name="twilio-campaign-message" placeholder="Campaign Message" required>
                        <option value="">Please select a message</option>
                        <?php /* Dropdown box of pre-made messages. Need a database table for automated outgoing messages. */

                        foreach ($decoded_messages as $message) {
                            echo '<option value="' . $message->id . ' selected">' . $message->programmable_message_name . '</option>';
                        }

                        ?>
                    </select>
                    <p id="twilio-campaign-message-tip" class="form-text text-muted mb-2">Select a message for your campaign.</p>
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
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Create Campaign</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>