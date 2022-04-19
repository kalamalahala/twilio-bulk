<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/includes
 * @author     Tyler Karle <tyler.karle@icloud.com>
 */
class Twilio_Bulk_Deactivator {

	/**
	 * Bulk Messaging Deactivation.
	 *
	 * Drop all related tables.
	 *
	 * @since    0.1.0
	 */
	public static function deactivate() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$messages = $prefix . 'twilio_bulk_messages'; // Inbound and Outbound messages
		$conversations = $prefix . 'twilio_bulk_conversations'; // Conversation Tracker
		$contacts = $prefix . 'twilio_bulk_contacts'; // Contact List && Caller ID
		$uploads = $prefix . 'twilio_bulk_uploads'; // Uploads
		$campaigns = $prefix . 'twilio_bulk_campaigns'; // Campaigns
		$sql[] = "DROP TABLE $messages;";
		$sql[] = "DROP TABLE $conversations;";
		$sql[] = "DROP TABLE $contacts;";
		$sql[] = "DROP TABLE $uploads;";
		$sql[] = "DROP TABLE $campaigns;";

		foreach ($sql as $query) {
			$wpdb->query($query);
		}
	}

}
