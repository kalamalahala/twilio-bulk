<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/kalamalahala/
 * @since      1.0.0
 *
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/admin
 * @author     Tyler Karle <tyler.karle@icloud.com>
 */

// Include Composer Autoloader
require ( plugin_dir_path( __FILE__ ) ) . '../vendor/autoload.php';
require ( plugin_dir_path( __FILE__ ) ) . 'classes/phpspreadsheet-handler.php';
require ( plugin_dir_path( __FILE__ ) ) . '../includes/class-twilio-bulk-ajax-handler.php';

use Twilio\Rest\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use twilio_bulk\TwilioBulkAjax;

class Twilio_Bulk_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Twilio_Bulk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Twilio_Bulk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Bootstrap on Admin Panel by Custom Wrapper: https://blog.shaharia.com/add-bootstrap-css-in-wordpress-plugin-page
		wp_enqueue_style('bootstrap-admin-wrapper', plugin_dir_url(__FILE__) . 'css/bootstrap-admin-wrapper.css', array(), $this->version, 'all');

		// FontAwesome
		wp_enqueue_style('font-awesome-css', plugin_dir_url(__FILE__) . 'css/font-awesome.css', array(), null, 'all');

		// jQuery UI css
		wp_enqueue_style('jquery-ui-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.css', array(), null, 'all');
		
		// jQuery Datetimepicker css
		wp_enqueue_style('jquery-datetimepicker-css', plugin_dir_url(__FILE__) . 'css/jquery.datetimepicker.min.css', array(), null, 'all');

		// Bootstrap Datetimepicker CSS
		// wp_enqueue_style('bootstrap-datetimepicker-css', plugin_dir_url(__FILE__) . 'css/bootstrap-datetimepicker.min.css', array(), null, 'all');


		// Manual styles as last layer
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/twilio-bulk-admin.css', array(), null, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Twilio_Bulk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Twilio_Bulk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//bootstrap js
		wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'js/bootstrap.js', array('jquery'), $this->version, false);
		// jQuery UI js
		wp_enqueue_script('jquery-ui-js', plugin_dir_url(__FILE__) . 'js/jquery-ui.js', array('jquery'), $this->version, false);
		
		// if page=twilio-bulk-new-campaign, load tempusdominus js
		if (isset($_GET['page']) && $_GET['page'] == 'twilio-bulk-new-campaign') {
			// momentjs
			wp_enqueue_script('moment-js', plugin_dir_url(__FILE__) . 'js/moment.js', array('jquery'), $this->version, false);
			// popper.min.js
			wp_enqueue_script('popper-js', plugin_dir_url(__FILE__) . 'js/popper.min.js', array('jquery'), $this->version, false);
			// jquery-datetimepicker js
			wp_enqueue_script('jquery-datetimepicker-js', plugin_dir_url(__FILE__) . 'js/jquery.datetimepicker.full.min.js', array('jquery'), $this->version, false);
			
		}
		
		// wp_localize_script to point to admin-ajax.php
		wp_enqueue_script('twilio_bulk_ajax', plugin_dir_url(__FILE__) . 'js/twilio-bulk-admin.js', array('jquery'), $this->version, false);
		wp_localize_script('twilio_bulk_ajax', 'twilio_bulk_ajax', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('twilio_bulk_nonce')));
	}

	// Callback function for Loader class to create Main menu on Admin Dashboard
	public function twilio_bulk_admin_menu()
	{

		// Dashboard Menu
		add_menu_page('Twilio Bulk Text', 'Bulk Messaging', 'manage_options', 'twilio-bulk-dashboard', array($this, 'twilio_bulk_admin_page'), 'dashicons-format-chat', 26);

		// Programmable Messages
		add_submenu_page('twilio-bulk-dashboard', 'Programmable Messages', 'Programmable Messages', 'manage_options', 'twilio-bulk-programmable-messages', array($this, 'twilio_bulk_programmable_messages_page'));
		add_submenu_page('twilio-bulk-dashboard', 'Create New Programmable Message', 'Create New Programmable Message', 'manage_options', 'twilio-bulk-programmable-messages-create', array($this, 'twilio_bulk_programmable_messages_create_page'));

		// Contacts Menu
		add_submenu_page('twilio-bulk-dashboard', 'Contacts', 'Contacts', 'manage_options', 'twilio-bulk-contacts', array($this, 'twilio_bulk_contacts_page'));

		// Campaigns Menu
		add_submenu_page('twilio-bulk-dashboard', 'Create New Campaign', 'Create New Campaign', 'manage_options', 'twilio-bulk-new-campaign', array($this, 'twilio_bulk_new_campaign_page'));
		add_submenu_page('twilio-bulk-dashboard', 'Campaigns', 'View Campaigns', 'manage_options', 'twilio-bulk-campaigns', array($this, 'twilio_bulk_campaigns_page'));


		// Reports Menu
		add_submenu_page('twilio-bulk-dashboard', 'Reports', 'Reports', 'manage_options', 'twilio-bulk-reports', array($this, 'twilio_bulk_reports_page'));
	}

	// Callback function for Loader to register settings
	public function twilio_bulk_admin_settings()
	{
		register_setting('twilio_bulk_settings_group', 'twilio_account_sid');
		register_setting('twilio_bulk_settings_group', 'twilio_account_auth_token');
		register_setting('twilio_bulk_settings_group', 'twilio_account_phone_number');
		// Later on add more settings
		// register_setting( 'twilio_bulk_settings_group', '' );
		// register_setting( 'twilio_bulk_settings_group', '' );
	}

	// Callback function for twilio_bulk_admin_menu
	public function twilio_bulk_admin_page()
	{
		// buffer output
		ob_start();
		include_once(plugin_dir_path(__FILE__) . 'partials/twilio-bulk-admin-dashboard.php');
		$output = ob_get_clean();
		echo $output;
	}

	// Callback function for twilio_bulk_programmable_messages_page: Read, Delete
	public function twilio_bulk_programmable_messages_page()
	{
		// buffer output
		ob_start();
		include_once(plugin_dir_path(__FILE__) . 'partials/programmable-messages/read.php');
		$output = ob_get_clean();
		echo $output;
	}

	// Callback function for twilio_bulk_programmable_messages_create: Create
	public function twilio_bulk_programmable_messages_create_page()
	{
		// buffer output
		ob_start();
		include_once(plugin_dir_path(__FILE__) . 'partials/programmable-messages/create.php');
		$output = ob_get_clean();
		echo $output;
	}

	// Callback function for twilio_bulk_programmable_messages_update: Update
	public function twilio_bulk_programmable_messages_update_page()
	{
		// buffer output
		ob_start();
		include_once(plugin_dir_path(__FILE__) . 'partials/programmable-messages/update.php');
		$output = ob_get_clean();
		echo $output;
	}


	public function twilio_bulk_campaigns_page()
	{
		// include_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-admin-campaigns.php' );
		echo '<h1>Campaigns</h1>';
	}

	public function twilio_bulk_new_campaign_page()
	{
		// buffer output
		ob_start();
		include_once(plugin_dir_path(__FILE__) . 'partials/twilio-bulk-admin-new-campaign.php');
		$output = ob_get_clean();
		echo $output;
	}

	public function twilio_bulk_contacts_page()
	{
		// buffer output
		ob_start();
		include_once(plugin_dir_path(__FILE__) . 'partials/contacts/contacts-read.php');
		$output = ob_get_clean();
		echo $output;

	}

	public function twilio_bulk_reports_page()
	{
		// include_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-admin-reports.php' );
		echo '<h1>Reports</h1>';
	}


	// All CRUD Functions for All Tables Defined Below

	// Begin CRUD functions for Programmable Messages
	// Create Programmable Message
	public function create_programmable_message()
	{
		// Get POST Data
		$post_data = $_POST;
		$message_body = $post_data['message_body'];
		$message_type = $post_data['message_type'];
		$message_name = $post_data['message_name'];
		$message_description = $post_data['message_description'];
		$message_uid = hash('md5', $message_body . $message_type . $message_name . $message_description);

		// INSERT message information into programmable messages database
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_programmable_messages';
		$wpdb->insert(
			$table_name,
			array(
				'message_uid' => $message_uid,
				'message_body' => $message_body,
				'message_type' => $message_type,
				'message_name' => $message_name,
				'message_description' => $message_description,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);
	}

	// Read Programmable Message
	public function read_programmable_message()
	{
		// Get POST Data
		$post_data = $_POST;
		$message_uid = $post_data['message_uid'];

		// Get Programmable Message
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_programmable_messages';
		$message = $wpdb->get_row("SELECT * FROM $table_name WHERE message_uid = '$message_uid'");

		// Return Programmable Message
		return json_encode($message);
	}

	// Get List of Messages
	public function get_programmable_messages(int $id = 0, bool $single = true)
	{
		// Get Programmable Messages
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_programmable_messages';

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
		return json_encode($messages);
	}

	// Update Programmable Message
	public function update_programmable_message()
	{
		// Get POST Data
		$post_data = $_POST;
		$message_uid = $post_data['message_uid'];
		$message_body = $post_data['message_body'];
		$message_type = $post_data['message_type'];
		$message_name = $post_data['message_name'];
		$message_description = $post_data['message_description'];

		// Update Programmable Message
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_programmable_messages';
		$wpdb->update(
			$table_name,
			array(
				'message_body' => $message_body,
				'message_type' => $message_type,
				'message_name' => $message_name,
				'message_description' => $message_description,
			),
			array(
				'message_uid' => $message_uid,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
			),
			array(
				'%s'
			)
		);
	}

	// Delete Programmable Message
	public function delete_programmable_message($uid)
	{
		// Get POST Data
		// $post_data = $_POST;
		// Delete Programmable Message
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_programmable_messages';
		$deletion = $wpdb->delete($table_name, array('programmable_message_uid' => $uid));
		return $deletion;
	}

	// Create Message to Contact
	public function create_message()
	{
		// Get POST Data
		$post_data = $_POST;
		$message_uid = $post_data['message_uid'];
		$contact_uid = $post_data['contact_uid'];
		$message_status = $post_data['message_status'];
		$message_date = $post_data['message_date'];
		$message_direction = $post_data['message_direction'];
		$message_body = $post_data['message_body'];
		$message_from = $post_data['message_from'];
		$message_to = $post_data['message_to'];
		$message_media_url = $post_data['message_media_url'];
		$message_media_type = $post_data['message_media_type'];
		$message_media_size = $post_data['message_media_size'];
		$message_media_format = $post_data['message_media_format'];
		$conversation_uid = $post_data['conversation_uid'];

		// Insert Message into Database
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_messages';
		$wpdb->insert(
			$table_name,
			array(
				'message_uid' => $message_uid,
				'contact_uid' => $contact_uid,
				'message_status' => $message_status,
				'message_date' => $message_date,
				'message_direction' => $message_direction,
				'message_body' => $message_body,
				'message_from' => $message_from,
				'message_to' => $message_to,
				'message_media_url' => $message_media_url,
				'message_media_type' => $message_media_type,
				'message_media_size' => $message_media_size,
				'message_media_format' => $message_media_format,
				'conversation_uid' => $conversation_uid,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);

		// If message is outgoing, send message
		if ($message_direction == 'outgoing') {
			// Send Message
			$this->send_message($message_uid);
		}

		return json_encode($message_uid);
	}

	// Send Message if Outgoing
	public function send_message($message_uid)
	{
		// Get Message
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_messages';
		$message = $wpdb->get_row("SELECT * FROM $table_name WHERE message_uid = '$message_uid'");

		// Send Message if Type is SMS
		if ($message->message_type == 'sms') {
			// Send SMS
			$this->send_sms($message);
		}

		// Send Message if Type is Email
		if ($message->message_type == 'email') {
			// Send Email
			$this->send_email($message);
		}

		return json_encode($message_uid);
	}

	// Send SMS
	public function send_sms($message)
	{
		// Get API Keys from Options
		$account_sid = get_option('twilio_account_sid');
		$auth_token = get_option('twilio_auth_token');

		// Get Contact
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_contacts';
		$contact = $wpdb->get_row("SELECT * FROM $table_name WHERE contact_uid = '$message->contact_uid'");

		// Create Conversation if it doesn't exist
		if ($message->conversation_uid == '') {
			// Create Conversation
			$conversation_uid = $this->create_conversation($message->contact_uid);
		} else {
			// Get Conversation
			$conversation_uid = $message->conversation_uid;
		}

		// Get Twilio Client
		$client = new Client($account_sid, $auth_token);

		// Send SMS
		$client->account->messages->create(array(
			'To' => $contact->contact_phone,
			'From' => $message->message_from,
			'Body' => $message->message_body,
		));
	}

	// Send Email
	public function send_email($message)
	{
		// Send wordpress email to contact
		$to = $message->contact_email;
		// $subject = $message->message_subject;
		$subject = 'Testing WP_Mail functionality';
		$message = $message->message_body;
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($to, $subject, $message, $headers);
	}

	// Get Message or Messages
	public function get_message()
	{
		// Get POST Data
		$post_data = $_POST;
		$message_uid = $post_data['message_uid'];

		// Get Message
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_messages';
		$message = $wpdb->get_row("SELECT * FROM $table_name WHERE message_uid = '$message_uid'");

		// Return Message
		return json_encode($message);
	}

	// Delete Message
	public function delete_message()
	{
		// Get POST Data
		$post_data = $_POST;
		$message_uid = $post_data['message_uid'];

		// Delete Message
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_messages';
		$wpdb->delete($table_name, array('message_uid' => $message_uid));

		return json_encode($message_uid);
	}

	// CRUD Options for Conversations
	// Create New Conversation
	public function create_conversation($contact_uid)
	{
		// Get POST Data
		$post_data = $_POST;
		$conversation_uid = $post_data['conversation_uid'];
		$conversation_name = $post_data['conversation_name'];
		$conversation_status = $post_data['conversation_status'];
		$conversation_participants = $post_data['conversation_participants'];
		$conversation_inbound_message_count = $post_data['conversation_inbound_message_count'];
		$conversation_outbound_message_count = $post_data['conversation_outbound_message_count'];
		$conversation_last_message_date = $post_data['conversation_last_message_date'];
		$conversation_last_message_from = $post_data['conversation_last_message_from'];
		$campaign_uid = $post_data['campaign_uid'];

		// Add Conversation to Database
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_conversations';
		$wpdb->insert(
			$table_name,
			array(
				'conversation_uid' => $conversation_uid,
				'conversation_name' => $conversation_name,
				'conversation_status' => $conversation_status,
				'conversation_participants' => $conversation_participants,
				'conversation_inbound_message_count' => $conversation_inbound_message_count,
				'conversation_outbound_message_count' => $conversation_outbound_message_count,
				'conversation_last_message_date' => $conversation_last_message_date,
				'conversation_last_message_from' => $conversation_last_message_from,
				'campaign_uid' => $campaign_uid,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);

		return $conversation_uid;
	}

	// Get Conversation
	public function get_conversation()
	{
		// Get POST Data
		$post_data = $_POST;
		$conversation_uid = $post_data['conversation_uid'];

		// Get Conversation
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_conversations';
		$conversation = $wpdb->get_row("SELECT * FROM $table_name WHERE conversation_uid = '$conversation_uid'");

		// Return Conversation
		return json_encode($conversation);
	}

	// Update Conversation Participants and Status
	public function update_conversation()
	{
		// Get POST Data
		$post_data = $_POST;
		$conversation_uid = $post_data['conversation_uid'];
		$conversation_name = $post_data['conversation_name'];
		$conversation_status = $post_data['conversation_status'];
		$conversation_participants = $post_data['conversation_participants'];
		$conversation_inbound_message_count = $post_data['conversation_inbound_message_count'];
		$conversation_outbound_message_count = $post_data['conversation_outbound_message_count'];
		$conversation_last_message_date = $post_data['conversation_last_message_date'];
		$conversation_last_message_from = $post_data['conversation_last_message_from'];
		$campaign_uid = $post_data['campaign_uid'];

		// Update Conversation
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_conversations';
		$wpdb->update(
			$table_name,
			array(
				'conversation_name' => $conversation_name,
				'conversation_status' => $conversation_status,
				'conversation_participants' => $conversation_participants,
				'conversation_inbound_message_count' => $conversation_inbound_message_count,
				'conversation_outbound_message_count' => $conversation_outbound_message_count,
				'conversation_last_message_date' => $conversation_last_message_date,
				'conversation_last_message_from' => $conversation_last_message_from,
				'campaign_uid' => $campaign_uid,
			),
			array(
				'conversation_uid' => $conversation_uid,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			),
			array(
				'%s',
			)
		);

		return $conversation_uid;
	}

	// Delete Conversation
	public function delete_conversation()
	{
		// Get POST Data
		$post_data = $_POST;
		$conversation_uid = $post_data['conversation_uid'];

		// Delete Conversation
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_conversations';
		$wpdb->delete($table_name, array('conversation_uid' => $conversation_uid), array('%s'));

		return $conversation_uid;
	}

	// Campaign CRUD
	// Create New Campaign from File Read by PHPSpreadsheet
	public function create_campaign_from_file()
	{
		// Get POST Data
		$post_data = $_POST;
		$campaign_uid = $post_data['campaign_uid'];
		$campaign_name = $post_data['campaign_name'];
		$campaign_description = $post_data['campaign_description'];
		$campaign_status = $post_data['campaign_status'];
		$campaign_type = $post_data['campaign_type'];
		$campaign_programmable_message_id = $post_data['campaign_programmable_message_id'];
		$campaign_is_active = $post_data['campaign_is_active'];
		$campaign_is_archived = $post_data['campaign_is_archived'];
		$campaign_has_follow_up = $post_data['campaign_has_follow_up'];
		$campaign_follow_up_message_id = $post_data['campaign_follow_up_message_id'];
		$campaign_follow_up_message_date = $post_data['campaign_follow_up_message_date'];
		$campaign_has_second_follow_up = $post_data['campaign_has_second_follow_up'];
		$campaign_second_follow_up_message_id = $post_data['campaign_second_follow_up_message_id'];
		$campaign_second_follow_up_message_date = $post_data['campaign_second_follow_up_message_date'];

		// Create Campaign
		global $wpdb;
		$table_name = $wpdb->prefix . 'twilio_bulk_campaigns';
		$wpdb->insert(
			$table_name,
			array(
				'campaign_uid' => $campaign_uid,
				'campaign_name' => $campaign_name,
				'campaign_description' => $campaign_description,
				'campaign_status' => $campaign_status,
				'campaign_type' => $campaign_type,
				'campaign_programmable_message_id' => $campaign_programmable_message_id,
				'campaign_is_active' => $campaign_is_active,
				'campaign_is_archived' => $campaign_is_archived,
				'campaign_has_follow_up' => $campaign_has_follow_up,
				'campaign_follow_up_message_id' => $campaign_follow_up_message_id,
				'campaign_follow_up_message_date' => $campaign_follow_up_message_date,
				'campaign_has_second_follow_up' => $campaign_has_second_follow_up,
				'campaign_second_follow_up_message_id' => $campaign_second_follow_up_message_id,
				'campaign_second_follow_up_message_date' => $campaign_second_follow_up_message_date,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);

		return $campaign_uid;
	}

	// Move this function to its include file: /twilio-bulk/includes/class-twilio-bulk-ajax-handler.php
	/**
	 * Handle AJAX requests for all plugin methods.
	 */

	public function twilio_bulk_ajax_methods()
	{
		// Assemble init data for AJAX request
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		$method = (isset($_POST['twilio_bulk_action'])) ? $_POST['twilio_bulk_action'] : '';
		$formData = (isset($_POST)) ? $_POST : '';
		$nonce = (isset($_POST['nonce'])) ? $_POST['nonce'] : 'twilio_bulk_ajax_nonce';
		// $fileData = (isset($_FILES)) ? $_FILES : '';

		$ajax = new TwilioBulkAjax( $formData, $action, $method, null, null, $nonce );

			switch ($method) {
				case 'spreadsheet_initial_upload':
					// Handle upload before sending to AJAX class
					if (0 < $_FILES['file']['error']) {
						$response['error'] = 'Error: ' . $_FILES['file']['error'];
					} else { 
					// No errors, proceed with upload, move file with wordpress
					$spreadsheet_file = wp_upload_bits($_FILES['file']['name'], null, file_get_contents($_FILES['file']['tmp_name']));
					}
					// Get path to uploaded file
					$path = $spreadsheet_file['file'];

					// Send file to AJAX class
					$ajax->spreadsheet_initial_upload( $path );
					break;
				case 'campaign_submit':
					$ajax->campaign_submit();
					break;
				case 'get_programmable_messages':
					$ajax->get_programmable_messages(0, true);
					break;
				default:
					wp_send_json(array('error' => 'Method not found', 'POST' => $formData));
					wp_die();
			}
	}
		/* This has been moved to the Handler class in includes/class-twilio-bulk-ajax-handler.php */
}
