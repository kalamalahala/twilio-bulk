<?php
/**
* Primary Upload Form for Twilio-Bulk
*
* @package Twilio-Bulk
* @author  Tyler Karle
* @since   0.1.0
*
*/
?>
<form id="twilio-bulk-upload-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="twilio-bulk-upload-form" value="1">
    <input type="hidden" name="action" value="twilio_bulk_upload">
    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'twilio-bulk-upload' ); ?>">
    <input type="hidden" name="_wp_http_referer" value="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo wp_max_upload_size(); ?>">
    <input type="file" name="twilio-bulk-file">
    <input type="submit" value="Upload">
</form>