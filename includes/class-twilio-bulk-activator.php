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
		$programmable_messages = $prefix . 'twilio_bulk_programmable_messages'; // Programmable Messages
		
		$charset_collate = $wpdb->get_charset_collate();
		$sql = array(
			"CREATE TABLE $messages (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				message_uid VARCHAR(255) NOT NULL,
				message_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				message_from VARCHAR(255) NOT NULL,
				message_to VARCHAR(255) NOT NULL,
				message_body VARCHAR(255) NOT NULL,
				message_status VARCHAR(255) NOT NULL,
				message_direction VARCHAR(255) NOT NULL,
				message_media_url VARCHAR(255),
				message_media_type VARCHAR(255),
				message_media_size VARCHAR(255),
				message_media_format VARCHAR(255),
				conversation_uid VARCHAR(255) NOT NULL,
				campaign_uid VARCHAR(255) NOT NULL
			) $charset_collate;",

			"CREATE TABLE $conversations (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				conversation_uid VARCHAR(255) NOT NULL,
				conversation_date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
				campaign_description VARCHAR(255),
				campaign_status VARCHAR(255),
				campaign_programmable_message_id INT(9) NOT NULL,
				campaign_is_active BOOLEAN NOT NULL DEFAULT 1,
				campaign_is_archived BOOLEAN NOT NULL DEFAULT 0,
				campaign_has_follow_up BOOLEAN NOT NULL DEFAULT 0,
				campaign_follow_up_message_id INT(9) NOT NULL,
				campaign_follow_up_message_date DATETIME NOT NULL,
				campaign_has_second_follow_up BOOLEAN NOT NULL DEFAULT 0,
				campaign_second_follow_up_message_id INT(9) NOT NULL,
				campaign_second_follow_up_message_date DATETIME NOT NULL,			
				campaign_date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				campaign_date_updated DATETIME,
				campaign_date_deleted DATETIME,
				campaign_date_scheduled DATETIME,
				campaign_date_started DATETIME,
				campaign_date_completed DATETIME,
				campaign_date_canceled DATETIME,
				campaign_date_paused DATETIME,
				campaign_date_resumed DATETIME,
				campaign_date_queued DATETIME
			) $charset_collate;",

			"CREATE TABLE $contacts (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				contact_uid VARCHAR(255) NOT NULL,
				contact_name VARCHAR(255) NOT NULL,
				contact_phone INT NOT NULL,
				contact_email VARCHAR(255),
				contact_status VARCHAR(255),
				contact_disposition VARCHAR(255),
				contact_date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				contact_date_updated DATETIME,
				contact_date_deleted DATETIME,
				contact_date_last_message_sent DATETIME,
				contact_date_last_message_received DATETIME
			) $charset_collate;",

			"CREATE TABLE $uploads (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				upload_uid VARCHAR(255) NOT NULL,
				upload_name VARCHAR(255) NOT NULL,
				upload_type VARCHAR(255) NOT NULL,
				upload_url VARCHAR(255) NOT NULL,
				upload_date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				upload_date_updated DATETIME,
				upload_date_deleted DATETIME
			) $charset_collate;",

			"CREATE TABLE $programmable_messages (
				id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				programmable_message_uid VARCHAR(255) NOT NULL,
				programmable_message_name VARCHAR(255) NOT NULL,
				programmable_message_description VARCHAR(255) NOT NULL DEFAULT '',
				programmable_message_content VARCHAR(255) NOT NULL DEFAULT '',
				programmable_message_date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				programmable_message_date_updated DATETIME,
				programmable_message_date_last_message_sent DATETIME NOT NULL
			) $charset_collate;",

		);

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

	}
}
