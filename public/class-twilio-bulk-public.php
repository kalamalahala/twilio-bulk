<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/kalamalahala/
 * @since      1.0.0
 *
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/public
 * @author     Tyler Karle <tyler.karle@icloud.com>
 */
class Twilio_Bulk_Public {

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

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		
		wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), null, 'all' );
		wp_enqueue_style( 'font-awesome-css', plugin_dir_url( __FILE__ ) . 'css/font-awesome.css', array(), null, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/twilio-bulk-public.css', array(), $this->version, false );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		
		
		wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array(), false, false ); // Bootstrap JS
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/twilio-bulk-public.js', array(), false, false ); // Twilio Bulk JS


	}

	public static function include_template_file( $template ) {

		$endpoint = $_GET['twilio-bulk'];
		switch ($endpoint) {
			default:
				$content = __ROOT__ . '/twilio-bulk/public/partials/twilio-bulk-home.php';
				break;
		}
		return $content;
	}



}
