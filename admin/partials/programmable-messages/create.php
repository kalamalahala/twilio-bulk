<?php // form goes here eventually

/**
 *  Create programmable message
 */

/**
 * 			"CREATE TABLE $programmable_messages (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				programmable_message_uid VARCHAR(255) NOT NULL,
				programmable_message_name VARCHAR(255) NOT NULL,
				programmable_message_description VARCHAR(255) NOT NULL DEFAULT '',
				programmable_message_content VARCHAR(255) NOT NULL DEFAULT '',
				programmable_message_date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				programmable_message_date_updated DATETIME,
				programmable_message_date_last_message_sent DATETIME NOT NULL,
			) $charset_collate;",
 */

// Handle POST
if (isset($_POST['twilio_bulk_programmable_message_create'])) {
    global $wpdb;
    $programmable_messages = $wpdb->prefix . 'twilio_bulk_programmable_messages';
    $submission = array();
    $submission['programmable_message_uid'] = uniqid();
    $submission['programmable_message_name'] = $_POST['twilio_programmable_message_name'];
    $submission['programmable_message_description'] = $_POST['twilio_programmable_message_description'];
    $submission['programmable_message_content'] = $_POST['twilio_programmable_message_content'];
    $submission['programmable_message_date_created'] = date('Y-m-d H:i:s');

    $prepared_query = $wpdb->prepare("INSERT INTO $programmable_messages (programmable_message_uid, programmable_message_name, programmable_message_description, programmable_message_content, programmable_message_date_created) VALUES (%s, %s, %s, %s, %s)", $submission['programmable_message_uid'], $submission['programmable_message_name'], $submission['programmable_message_description'], $submission['programmable_message_content'], $submission['programmable_message_date_created']);
    $create_message = $wpdb->query($prepared_query);

    if ($create_message) {
        $message = '<div class="notice notice-success is-dismissible"><p>Programmable message created successfully.</p></div>';
    } else {
        $message = '<div class="notice notice-error is-dismissible"><p>Programmable message could not be created.</p></div>';
    }
}






?>

<div class="bootstrap-wrapper">
    <div class="jumbotron ">
        <?php if (isset($message)) {
            echo $message;
        } ?>
        <h1>Create New Programmable Message</h1>
        <p>Create a new programmable message and send it to your contacts!</p>
    </div>
    <div class="shadow p-4 mb-4 bg-white col-sm-5">
        <h2>Programmable Message Details</h2>

        <form action="/wp-admin/admin.php?page=twilio-bulk-programmable-messages-create" enctype="application/x-www-form-urlencoded" method="POST" autocomplete="new-password">
            <div class="row form-group">
                <label for="twilio-programmable-message-name" class="col-sm-2 col-form-label" aria-describedby="twilio-programmable-message-name-tip">Programmable Message Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="twilio-programmable-message-name" name="twilio_programmable_message_name" placeholder="Programmable Message Name" required>
                    <p id="twilio-programmable-message-name-tip" class="form-text text-muted mb-0">Enter a name for your programmable message.</p>
                </div>
            </div>
            <div class="row form-group">
                <label for="twilio-programmable-message-description" class="col-sm-2 col-form-label" aria-describedby="twilio-programmable-message-description-tip">Programmable Message Description</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="twilio-programmable-message-description" name="twilio_programmable_message_description" placeholder="Programmable Message Description" required>
                    <p id="twilio-programmable-message-description-tip" class="form-text text-muted mb-0">Enter a description for your programmable message.</p>
                </div>
            </div>
            <div class="row form-group">
                <label for="twilio-programmable-message-content" class="col-sm-2 col-form-label" aria-describedby="twilio-programmable-message-content-tip">Programmable Message Content</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="twilio-programmable-message-content" name="twilio_programmable_message_content" placeholder="Programmable Message Content" required></textarea>
                    <p id="twilio-programmable-message-content-tip" class="form-text text-muted mb-0">Enter the content for your programmable message.</p>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="row form-group">
                <div class="col-sm-10">
                    <button type="submit" name="twilio_bulk_programmable_message_create" class="btn btn-primary" value="create-new">Create Programmable Message</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>