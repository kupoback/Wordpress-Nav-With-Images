<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cliquestudios.com/
 * @since      1.0.0
 *
 * @package    Cs_Custom_Navigation
 * @subpackage Cs_Custom_Navigation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cs_Custom_Navigation
 * @subpackage Cs_Custom_Navigation/admin
 * @author     Nick Makris | Clique Studios <nmakris@cliquestudios.com>
 */
class CS_Custom_Navigation_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		//		add_filter('wp_edit_nav_menu_walker', [$this, 'csnw_nav_menu_edit']);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook)
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cs_Custom_Navigation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cs_Custom_Navigation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ('nav-menus.php' !== $hook)
			return $hook;

		wp_enqueue_style($this->plugin_name . '-menu-admin', plugin_dir_url(__FILE__) . 'css/csnw-admin.css', [], '', 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook)
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cs_Custom_Navigation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cs_Custom_Navigation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ('nav-menus.php' !== $hook)
			return $hook;

		wp_enqueue_media();
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cs-custom-navigation-admin.js', ['jquery'], '', true);

	}

	public function csnw_nav_menu_edit()
	{

		return 'CSNW_Nav_Menu_Edit';
	}

	/**
	 * Returns names for fields used to store/retrieve metadata.
	 *
	 * @return array The array of field names used.
	 * @since 1.0.0
	 *
	 */
	public function csnw_get_field_names()
	{

		$field_names = [
			'csnw-submenu-image',
		];

		return $field_names;
	}

	public function csnw_add_data_to_menu_item($menu_item)
	{

		if (isset($menu_item->ID)) :
			$field_names = $this->csnw_get_field_names();
			foreach ($field_names as $name) {
				$item_field             = str_replace('-', '_', $name);
				$meta_field             = '_' . $item_field;
				$value                  = get_post_meta($menu_item->ID, $meta_field, true);
				$menu_item->$item_field = $value;
			}
		endif;
		if (isset($menu_item->ID)) :
			$menu_item_parent = absint($menu_item->menu_item_parent);
			// get child count for top-level items
			if ('q' === $menu_item_parent) {
				$args     = [
					'meta_key'       => '_menu_item_menu_item_parent',
					'meta_value'     => $menu_item->db_id,
					'post_type'      => 'nav_menu_item',
					'posts_per_page' => 50,
					'post_status'    => 'publish',
				];
				$children = count(get_posts($args));
				if ($children > 0) {
					$menu_item->child_count = $children;
				}
			}
		endif;

		return $menu_item;
	}

	public function csnw_add_megamenu_fields($item_id, $item, $depth, $args)
	{

		$image_attributes = wp_get_attachment_image_src($item->csnw_submenu_image);
		$src              = $image_attributes[0];
		$alt              = get_post_meta($item->csnw_submenu_image, '_wp_attachment_image_alt', true);
		?>

		<p class="field-submenu-image description description-wide hidden-field sub-level">
			<?php printf(
				'<input type="hidden" value="%s" name="%s" data-name="hidden-media" />',
				$item->csnw_submenu_image,
				'csnw-submenu-image[' . $item_id . ']'
			); ?>
			<label for="<?php echo 'csnw-submenu-image[' . $item_id . ']'; ?>">
				<span class="image hidden">
					<img src="<?php echo $src; ?>" data-src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" data-name="media" />
				</span>
				<span class="buttons">
					<input type="button" class="button button-primary" value="Select Image" data-name="add">
					<input type="button" class="button button-primary hidden" value="Change Image" data-name="edit">
					<input type="button" class="button button-delete hidden" value="Remove Image" data-name="remove" />
				</span>
			</label>
		</p>

		<?php

	}

	public function csnw_nav_menu_save_fields($menu_id, $menu_item_db_id, $menu_item_data)
	{

		$field_names = $this->csnw_get_field_names();
		foreach ($field_names as $name) {
			$meta_field = '_' . str_replace('-', '_', $name);
			if (empty($_REQUEST[$name][$menu_item_db_id])) {
				delete_post_meta($menu_item_db_id, $meta_field);
			}
			else {
				$meta_value = trim($_REQUEST[$name][$menu_item_db_id]);
				update_post_meta($menu_item_db_id, $meta_field, $meta_value);
			}
		}
	}

	public function csnw_add_image_size() {
		add_image_size('menu', 160, 140, false );
	}

}
