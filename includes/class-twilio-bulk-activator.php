<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/includes
 * @author     Tyler Karle <tyler.karle@icloud.com>
 */
class Twilio_Bulk_Activator
{

	/**
	 * Plugin Activation
	 *
	 * Initialize databases, templates, themes, etc.
	 *
	 * @since    0.1.0
	 */
	public static function activate()
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$messages = $prefix . 'twilio_bulk_messages'; // Inbound and Outbound messages
		$conversations = $prefix . 'twilio_bulk_conversations'; // Conversation Tracker
		$contacts = $prefix . 'twilio_bulk_contacts'; // Contact List && Caller ID
		$uploads = $prefix . 'twilio_bulk_uploads'; // Uploads
		$campaigns = $prefix . 'twilio_bulk_campaigns'; // Campaigns
		
		$charset_collate = $wpdb->get_charset_collate();
		$sql = array(
			"CREATE TABLE $messages (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				message_uid VARCHAR(255) NOT NULL,
				message_date DATETIME NOT NULL,
				message_from VARCHAR(255) NOT NULL,
				message_to VARCHAR(255) NOT NULL,
				message_body VARCHAR(255) NOT NULL,
				message_status VARCHAR(255) NOT NULL,
				message_direction VARCHAR(255) NOT NULL,
				message_media_url VARCHAR(255) NOT NULL,
				message_media_type VARCHAR(255) NOT NULL,
				message_media_size VARCHAR(255) NOT NULL,
				message_media_format VARCHAR(255) NOT NULL,
				conversation_uid VARCHAR(255) NOT NULL,
				campaign_uid VARCHAR(255) NOT NULL
			) $charset_collate;",

			"CREATE TABLE $conversations (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				conversation_uid VARCHAR(255) NOT NULL,
				conversation_date_created DATETIME NOT NULL,
				conversation_participants VARCHAR(255) NOT NULL,
				conversation_status VARCHAR(255) NOT NULL,
				conversation_inbound_message_count VARCHAR(255) NOT NULL,
				conversation_outbound_message_count VARCHAR(255) NOT NULL,
				conversation_last_message_date DATETIME NOT NULL,
				conversation_last_message_from VARCHAR(255) NOT NULL,
				campaign_uid VARCHAR(255) NOT NULL
			) $charset_collate;",

			"CREATE TABLE $campaigns (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				campaign_uid VARCHAR(255) NOT NULL,
				campaign_name VARCHAR(255) NOT NULL,
				campaign_description VARCHAR(255) NOT NULL,
				campaign_status VARCHAR(255) NOT NULL,
				campaign_date_created DATETIME NOT NULL,
				campaign_date_updated DATETIME NOT NULL,
				campaign_date_deleted DATETIME NOT NULL,
				campaign_date_scheduled DATETIME NOT NULL,
				campaign_date_started DATETIME NOT NULL,
				campaign_date_completed DATETIME NOT NULL,
				campaign_date_canceled DATETIME NOT NULL,
				campaign_date_paused DATETIME NOT NULL,
				campaign_date_resumed DATETIME NOT NULL,
				campaign_date_queued DATETIME NOT NULL,
				campaign_date_queued_for_sending DATETIME NOT NULL,
				campaign_date_queued_for_sending_completed DATETIME NOT NULL,
				campaign_date_queued_for_sending_failed DATETIME NOT NULL,
				campaign_date_queued_for_sending_canceled DATETIME NOT NULL,
				campaign_date_queued_for_sending_paused DATETIME NOT NULL,
				campaign_date_queued_for_sending_resumed DATETIME NOT NULL,
				campaign_date_queued_for_sending_resumed_completed DATETIME NOT NULL,
				campaign_date_queued_for_sending_resumed_failed DATETIME NOT NULL,
				campaign_date_queued_for_sending_resumed_canceled DATETIME NOT NULL
			) $charset_collate;",

			"CREATE TABLE $contacts (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				contact_uid VARCHAR(255) NOT NULL,
				contact_name VARCHAR(255) NOT NULL,
				contact_phone VARCHAR(255) NOT NULL,
				contact_email VARCHAR(255) NOT NULL,
				contact_status VARCHAR(255) NOT NULL,
				contact_date_created DATETIME NOT NULL,
				contact_date_updated DATETIME NOT NULL,
				contact_date_deleted DATETIME NOT NULL,
				contact_date_last_message_sent DATETIME NOT NULL,
				contact_date_last_message_received DATETIME NOT NULL
			) $charset_collate;",

			"CREATE TABLE $uploads (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				upload_uid VARCHAR(255) NOT NULL,
				upload_name VARCHAR(255) NOT NULL,
				upload_type VARCHAR(255) NOT NULL,
				upload_size VARCHAR(255) NOT NULL,
				upload_url VARCHAR(255) NOT NULL,
				upload_date_created DATETIME NOT NULL,
				upload_date_updated DATETIME NOT NULL,
				upload_date_deleted DATETIME NOT NULL,
				upload_date_last_message_sent DATETIME NOT NULL,
				upload_date_last_message_received DATETIME NOT NULL
			) $charset_collate;",

		);

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

	}
}
