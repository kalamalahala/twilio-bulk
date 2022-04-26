<?php

/**
 *  List created programmable messages
 */

 // Programmable Messages Columns:
 // id, programmable_message_uid, programmable_message_name, programmable_message_description, programmable_message_content, programmable_message_date_created, programmable_message_date_updated, programmable_message_date_last_message_sent

$messages = $this->get_programmable_messages();
$messages = json_decode( $messages );

$messages_list = '';
// Add options to a select element
foreach ( $messages as $message ) {
    $messages_list .= '<option value="' . $message->id . '">' . $message->programmable_message_name . '</option>';
}

// Handle Delete Button Click
// twilio_bulk_programmable_messages - Programmable Messages - Delete

if ( isset($_GET['message-delete']) && $_GET['id'] !== '' ) {
    $id = $_GET['id'];
    $delete_programmable_message = $this->delete_programmable_message( $id );
    if ( $delete_programmable_message ) {
        $delete_result = '<div class="alert alert-success">Programmable Message Deleted</div>';
    } else {
        $delete_result = '<div class="alert alert-danger">Programmable Message Not Deleted</div>';
    }
}

?>

<div class="bootstrap-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Programmable Messages</h1>
                <?php // echo $delete_result;
                if ( isset($delete_result) ) {
                    echo $delete_result;
                }
                ?>
                <p>
                    <a href="<?php echo admin_url( 'admin.php?page=twilio-bulk-programmable-messages-create' ); ?>" class="btn btn-primary">Create New Programmable Message</a>
                </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Content</th>
                            <th>Date Created</th>
                            <th>Date Updated</th>
                            <th>Date Last Message Sent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $messages as $message ) : ?>
                            <tr>
                                <td><?php echo $message->id; ?></td>
                                <td><?php echo $message->programmable_message_name; ?></td>
                                <td><?php echo $message->programmable_message_description; ?></td>
                                <td><?php echo $message->programmable_message_content; ?></td>
                                <td><?php echo $message->programmable_message_date_created; ?></td>
                                <td><?php echo $message->programmable_message_date_updated; ?></td>
                                <td><?php echo $message->programmable_message_date_last_message_sent; ?></td>
                                <td>
                                    <a href="<?php echo admin_url( 'admin.php?page=twilio-bulk-programmable-messages-update&id=' . $message->programmable_message_uid ); ?>" class="btn btn-primary">Update</a>
                                    <a href="<?php echo admin_url( 'admin.php?page=twilio-bulk-programmable-messages&message-delete=y&id=' . $message->programmable_message_uid ); ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


</div>