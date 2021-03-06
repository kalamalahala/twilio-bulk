<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    Twilio_Bulk
 * @subpackage Twilio_Bulk/includes
 * @author     Tyler Karle <tyler.karle@icloud.com>
 */
class Twilio_Bulk
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Twilio_Bulk_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function __construct()
	{
		if (defined('TWILIO_BULK_VERSION')) {
			$this->version = TWILIO_BULK_VERSION;
		} else {
			$this->version = '0.1.0';
		}
		$this->plugin_name = 'twilio-bulk';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Twilio_Bulk_Loader. Orchestrates the hooks of the plugin.
	 * - Twilio_Bulk_i18n. Defines internationalization functionality.
	 * - Twilio_Bulk_Admin. Defines all hooks for the admin area.
	 * - Twilio_Bulk_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-twilio-bulk-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-twilio-bulk-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __FILE__ ) . '../admin/class-twilio-bulk-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __FILE__ ) . '../public/class-twilio-bulk-public.php';

		/** 
		 * AJAX Handler Class
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-twilio-bulk-ajax-handler.php';

		$this->loader = new Twilio_Bulk_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Twilio_Bulk_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Twilio_Bulk_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Callback functions for add_action and add_filter are located in the ../admin/class-twilio-bulk-admin.php
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Twilio_Bulk_Admin($this->get_plugin_name(), $this->get_version());
		// AJAX hook
		$this->loader->add_action( 'wp_ajax_twilio_bulk', $plugin_admin, 'twilio_bulk_ajax_methods' );
		// Add admin menu and settings
		$this->loader->add_action('admin_menu', $plugin_admin, 'twilio_bulk_admin_menu');
		// Add Menu to admin bar
		$this->loader->add_action('admin_bar_menu', $plugin_admin, 'twilio_bulk_admin_bar_menu', 100);
		// Register Wordpress Options to be stored in the database
		$this->loader->add_action('admin_init', $plugin_admin, 'twilio_bulk_admin_settings');

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');


		// Include AJAX methods and add_action
		// require_once plugin_dir_path(  __FILE__ ) . 'admin/class-twilio-bulk-ajax-methods.php';


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Twilio_Bulk_Public($this->get_plugin_name(), $this->get_version());

		if (isset($_GET['twilio-bulk'])) { // Add CSS and JS when twilio-bulk is a parameter in the URL
			$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
			$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
			$this->loader->add_filter('page_template', $plugin_public, 'include_template_file');
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1.0
	 * @return    Twilio_Bulk_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}


}
