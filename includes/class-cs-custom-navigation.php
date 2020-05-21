<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cliquestudios.com/
 * @since      1.0.0
 *
 * @package    Cs_Custom_Navigation
 * @subpackage Cs_Custom_Navigation/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cs_Custom_Navigation
 * @subpackage Cs_Custom_Navigation/includes
 * @author     Nick Makris | Clique Studios <nmakris@cliquestudios.com>
 */
class Cs_Custom_Navigation
{
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cs_Custom_Navigation_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		
		if (defined('CS_CUSTOM_NAVIGATION_VERSION')) {
			$this->version = CS_CUSTOM_NAVIGATION_VERSION;
		}
		else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'cs-custom-navigation';
		
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
	 * - Cs_Custom_Navigation_Loader. Orchestrates the hooks of the plugin.
	 * - Cs_Custom_Navigation_i18n. Defines internationalization functionality.
	 * - Cs_Custom_Navigation_Admin. Defines all hooks for the admin area.
	 * - Cs_Custom_Navigation_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cs-custom-navigation-loader.php';
		
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cs-custom-navigation-i18n.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cs-custom-navigation-admin.php';
		
		/**
		 * The class responsible for adjusting the menu markup.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cs-custom-navigation-menu-edit.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cs-custom-navigation-public.php';
		
		/**
		 * This class is responsible for the HTML of the nav menu
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cs-custom-navigation-menu-front.php';
		
		$this->loader = new Cs_Custom_Navigation_Loader();
		
	}
	
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cs_Custom_Navigation_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{
		
		$plugin_i18n = new Cs_Custom_Navigation_i18n();
		
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
		
	}
	
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		
		$plugin_admin = new CS_Custom_Navigation_Admin($this->get_plugin_name(), $this->get_version());
		
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_filter('init', $plugin_admin, 'csnw_add_image_size');
		$this->loader->add_filter('wp_edit_nav_menu_walker', $plugin_admin, 'csnw_nav_menu_edit');
		$this->loader->add_action('wp_nav_menu_item_custom_fields', $plugin_admin, 'csnw_add_megamenu_fields', 10, 4);
		$this->loader->add_action('wp_update_nav_menu_item', $plugin_admin, 'csnw_nav_menu_save_fields', 10, 3);
		$this->loader->add_filter('wp_setup_nav_menu_item', $plugin_admin, 'csnw_add_data_to_menu_item');
		
	}
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		
		$plugin_public = new CS_Custom_Navigation_Public($this->get_plugin_name(), $this->get_version());
		
//		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
//		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		
	}
	
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		
		$this->loader->run();
	}
	
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name()
	{
		
		return $this->plugin_name;
	}
	
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Cs_Custom_Navigation_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader()
	{
		
		return $this->loader;
	}
	
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version()
	{
		
		return $this->version;
	}
	
}
