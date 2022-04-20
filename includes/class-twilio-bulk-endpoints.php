<?php 

    /**
	 * API Route Constructor.
	 *
	 * @since    0.1.0
	 */

    class Twilio_Bulk_Endpoints {

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
         * @param      string    $plugin_name       The name of the plugin.
         * @param      string    $version    The version of this plugin.
         */
        public function __construct( $plugin_name, $version ) {

            $this->plugin_name = $plugin_name;
            $this->version = $version;

        }

	// public function twilio_bulk_register_endpoints() {

	// 	register_rest_route( '/twilio-bulk/v1', '/upload', array(
	// 		'methods' => 'GET',
	// 		'callback' => array( $this, 'twilio_bulk_get_upload_form' )
	// 	) );

	// 	// register_rest_route( '/example/v1', '/second-example', array(
	// 	// 	'methods' => 'GET',
	// 	// 	'callback' => array( $this, 'Boilerplate_custom_api_endpoint_second_example' )
	// 	// ) );

	// }

	/**
	 * API Endpoint first example.
	 *
	 * @since    0.1.0
	 */
	public function twilio_bulk_get_upload_form( ) {
        $content = require_once( plugin_dir_path( __FILE__ ) . 'partials/twilio-bulk-public-display.php' );
        return $content;
	}

}