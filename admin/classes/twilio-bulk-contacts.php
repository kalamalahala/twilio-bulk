<?php
/**
 * Contacts handler class for the Twilio Bulk SMS plugin.
 */

 /**
  *  Schema for the contacts table
  *      * Here are the columns that are needed:
         * - id
         * - contact_uid
         * - contact_name
         * - contact_phone
         * - contact_email
         * - contact_status
         * - contact_disposition
         * - contact_date_created timestamp
  */

 class TwilioBulkContacts {

        private $contacts_table;

        public function __construct() {
            global $wpdb;
            $this->contacts_table = $wpdb->prefix . 'twilio_bulk_contacts';
        }

        public function get_contacts() {
            global $wpdb;
            $contacts = $wpdb->get_results("SELECT * FROM {$this->contacts_table}");
            return $contacts;
        }

        public function get_contact($id) {
            global $wpdb;
            $contact = $wpdb->get_row("SELECT * FROM {$this->contacts_table} WHERE id = {$id}");
            return $contact;
        }

        /**
         * add_contact
         * 
         * Insert a new contact into the database.
         *
         * @param array $contact
         * @return int
         */
        public function add_contact($contact) {
            global $wpdb;
            $wpdb->insert($this->contacts_table, $contact);
            return $wpdb->insert_id;
        }

        public function update_contact($contact) {
            $contact['contact_date_updated'] = current_time('mysql');
            global $wpdb;
            $wpdb->update($this->contacts_table, $contact, array('id' => $contact['id']));
            return $wpdb->insert_id;
        }

        public function delete_contact($id) {
            global $wpdb;
            $wpdb->delete($this->contacts_table, array('id' => $id));
        }

        public function get_contact_by_phone_number($phone_number) {
            global $wpdb;
            $contact = $wpdb->get_row("SELECT * FROM {$this->contacts_table} WHERE phone_number = '{$phone_number}'");
            return $contact;
        }
 }