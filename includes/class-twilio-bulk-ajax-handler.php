<?php

/**
 * Handle AJAX requests for all plugin methods.
 */

namespace twilio_bulk;

use \SpreadSheetHandler;
define('NONCE', 'twilio_bulk_nonce');
define('ACTION', 'twilio_bulk_ajax');
define('ALLOW_UNFILTERED_UPLOADS', true);

class TwilioBulkAjax {

    /**
     * Constructor.
     */
    public function __construct($post_data = '', string $action = '', string $method = '', mixed $data = null, string $file = null, string $nonce = null)
    {    
        // Collect constructor data
        $this->action = $action;
        $this->method = $method;
        $this->data = $data;
        if ($file) {
            $this->file = $file;
        }
        if ($nonce) {
            $this->nonce = $nonce;
        }       

    }

    #region Spreadsheet related requests

    /**
     * Handles the initial upload of the contact list and returns some data about the file.
     *
     * @param string $path The path to the file.
     * @return json
     */
    public function spreadsheet_initial_upload(string $path)
    {
        // $file = $this->file;
        // foreach ($file as $key => $value) {
        //     $file[$key] = sanitize_text_field($value);
        // }
        try {
            $spreadsheet_handler = new SpreadSheetHandler(sanitize_text_field($path));
            $spreadsheet_json = $spreadsheet_handler->get_spreadsheet_json();
            // $response = $
        } catch (\Exception $e) {
            $spreadsheet_json = array(
                'error' => $e->getMessage(),
                '$_POST' => $_POST,
                '$_FILES' => $_FILES
            );
        } finally {
            wp_send_json($spreadsheet_json);                
            wp_die();
        }
    }
    #endregion
    #region Programmable Message CRUD Methods
    	// Get List of Messages
    /**
     * Queries the list of programmable messages set by the user.
     * 
     * Ajax is true by default, but can be set to false to return a JSON object.
     * 
     * @param int $id = 0
     * @param boolean $ajax = true
     * @return string as JSON
     */
	public function get_programmable_messages(int $id = 0, bool $ajax = true)
	{
		// Get Programmable Messages
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_programmable_messages';

        // If "programmable_message_id" is set in POST, set it to $id
        if (isset($_POST['programmable_message_id'])) {
            $id = intval($_POST['programmable_message_id']);
        }

		// If $id is an array, get all messages with the given IDs
		if (is_array($id)) {
			$messages = $wpdb->get_results("SELECT * FROM $table_name WHERE id IN (" . implode(',', $id) . ")", ARRAY_A);
		} else if ($id == 0) {
			// If $id is 0, get all messages
			$messages = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
		} else {
			// If $id is an integer, get the message with the given ID
			$messages = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $id", ARRAY_A);
		}

		if ($messages == null) {
			return 'Null found during query';
		}

		// Return Programmable Messages
        if ($ajax) {
            wp_send_json($messages);
            wp_die();
        } else {
            return json_encode($messages);
        }
	}
    #endregion
    #region Campaign CRUD Methods
    public function campaign_submit() {
        // Get POST data
        $post_data = $_POST;
        // Get nonce
        $nonce = $post_data['nonce'];
        // Verify nonce
        if (!wp_verify_nonce($nonce, NONCE)) {
            wp_send_json(array(
                'error' => 'Nonce verification failed.'
            ));
            wp_die();
        }

        // Handle uploaded file
        if (0 < $_FILES['file']['error']) {
            $response['error'] = 'Error: ' . $_FILES['file']['error'];
        } else { 
            // No errors, proceed with upload, move file with wordpress
            // Return the contents of $_FILES array
            $response['$_FILES'] = $_FILES;
            wp_send_json($response);
            wp_die();
            // try, catch, wp_die
            try {
                $spreadsheet_file = wp_upload_bits($_FILES['file']['name'] . '-processed', null, file_get_contents($_FILES['file']['tmp_name']));
                $response['file'] = $spreadsheet_file;
            } catch (\Exception $e) {
                wp_die($e->getMessage());
            }
        }
        // Get path to uploaded file
        $path = $spreadsheet_file['file'];
        
        $ssh = new SpreadSheetHandler($path);
        // wp_send_json(array(
        //     'success' => 'File uploaded successfully.',
        //     '$spreadsheet_file' => $spreadsheet_file,
        //     '$ssh' => $ssh
        // ));
        // wp_die("dump");

        // Collect Name, Phone Number, and Email columns from POST data as key => value pairs
        $columns = array();
        $column['name'] = $post_data['name_column'];
        $column['phone'] = $post_data['phone_column'];
        $column['email'] = $post_data['email_column'];

        $valid_contacts = $ssh->get_valid_contacts( $columns );
        // $invalid_contacts = $ssh->get_invalid_contacts();
        $response = array(
            'valid_contacts' => $valid_contacts
        );




        wp_send_json($response);
        wp_die();
    }
    #endregion
    #region Contact CRUD Methods
    #endregion
    #region Message CRUD Methods
    #endregion

} // End of class