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
class Twilio_Bulk_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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
		wp_enqueue_style( 'bootstrap-admin-wrapper', plugin_dir_url( __FILE__ ) . 'css/bootstrap-admin-wrapper.css', array(), $this->version, 'all' );

		// FontAwesome
		wp_enqueue_style( 'font-awesome-css', plugin_dir_url( __FILE__ ) . 'css/font-awesome.css', array(), null, 'all' );


		// Manual styles as last layer
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/twilio-bulk-admin.css', array(), null, 'all' ); 

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		// Hacky JS include for Admin Bootstrap by Rush Frisby: https://rushfrisby.com/using-bootstrap-in-wordpress-admin-panel
		// wp_enqueue_script('admin_js_bootstrap_hack', plugin_dir_url( __FILE__ ) . 'js/bootstrap-hack.js', false, '0.1.0', false);	

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/twilio-bulk-admin.js', array( 'jquery' ), false, false );

	}
	
	// Callback function for Loader class to create Main menu on Admin Dashboard
	public function twilio_bulk_admin_menu() {
		
		// Dashboard Menu
		add_menu_page( 'Twilio Bulk Text', 'Bulk Messaging', 'manage_options', 'twilio-bulk-dashboard', array( $this, 'twilio_bulk_admin_page' ), 'dashicons-format-chat', 26 );

		// Programmable Messages
		add_submenu_page( 'twilio-bulk-dashboard', 'Programmable Messages', 'Programmable Messages', 'manage_options', 'twilio-bulk-programmable-messages', array( $this, 'twilio_bulk_programmable_messages_page' ) );

		// Contacts Menu
		add_submenu_page( 'twilio-bulk-dashboard', 'Contacts', 'Contacts', 'manage_options', 'twilio-bulk-contacts', array( $this, 'twilio_bulk_contacts_page' ) );
		
		// Campaigns Menu
		add_submenu_page( 'twilio-bulk-dashboard', 'Create New Campaign', 'Create New Campaign', 'manage_options', 'twilio-bulk-new-campaign', array( $this, 'twilio_bulk_new_campaign_page' ) );
		add_submenu_page( 'twilio-bulk-dashboard', 'Campaigns', 'View Campaigns', 'manage_options', 'twilio-bulk-campaigns', array( $this, 'twilio_bulk_campaigns_page' ) );


		// Reports Menu
		add_submenu_page( 'twilio-bulk-dashboard', 'Reports', 'Reports', 'manage_options', 'twilio-bulk-reports', array( $this, 'twilio_bulk_reports_page' ) );

	}

	// Callback function for Loader to register settings
	public function twilio_bulk_admin_settings() {
		register_setting( 'twilio_bulk_settings_group', 'twilio_account_sid' );
		register_setting( 'twilio_bulk_settings_group', 'twilio_account_auth_token' );
		register_setting( 'twilio_bulk_settings_group', 'twilio_account_phone_number' );
		// Later on add more settings
		// register_setting( 'twilio_bulk_settings_group', '' );
		// register_setting( 'twilio_bulk_settings_group', '' );
	}

	// Callback function for twilio_bulk_admin_menu
	public function twilio_bulk_admin_page() {
		// buffer output
		ob_start();
		include_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-admin-dashboard.php' );
		$output = ob_get_clean();
		echo $output;
	}

	// Callback function for twilio_bulk_programmable_messages_create
	public function twilio_bulk_programmable_messages_page() {
		// buffer output
		ob_start();
		include_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-programmable-messages.php' );
		$output = ob_get_clean();
		echo $output;
	}


	public function twilio_bulk_campaigns_page() {
		// include_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-admin-campaigns.php' );
		echo '<h1>Campaigns</h1>';
	}

	public function twilio_bulk_new_campaign_page() {
		// buffer output
		ob_start();
		include_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-admin-new-campaign.php' );
		$output = ob_get_clean();
		echo $output;
	}

	public function twilio_bulk_contacts_page() {
		// include_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-admin-contacts.php' );
		echo '<h1>Contacts</h1>';
	}

	public function twilio_bulk_reports_page() {
		// include_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-admin-reports.php' );
		echo '<h1>Reports</h1>';
	}



}
