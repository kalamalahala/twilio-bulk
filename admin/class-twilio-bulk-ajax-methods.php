<?php

/**
 * Handle AJAX requests for all plugin methods.
 */

function twilio_bulk_ajax_methods() {
    check_ajax_referer('twilio_bulk_nonce');
    $crud_method = $_POST['crud_method'];
    $response = array();
    $resonse['success'] = false;
    $response['message'] = 'Method sent to AJAX: ' . esc_html( $crud_method );

    $response_json = json_encode($response);
    wp_send_json($response_json);
}