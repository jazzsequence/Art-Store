<?php
/**
 * Options class
 * Controls the Art Store options page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Art_Store_Options' ) ) {

	class Art_Store_Options {

		/**
		 * Option key and option page slug
		 *
		 * @var string
		 */
		private $key = 'art_store_options';

		/**
		 * Options page metabox id
		 *
		 * @var string
		 */
		private $metabox_id = 'art_store_option_metabox';

		/**
		 * Array of metaboxes/fields
		 *
		 * @var array
		 */
		protected $option_metabox = array();

		/**
		 * Options Page Title
		 *
		 * @var string
		 */
		protected $title = '';

		/**
		 * Options Page hook
		 *
		 * @var string
		 */
		protected $options_page = '';

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			$Art_Store            = new Art_Store;
			$this->basename       = $Art_Store->basename;
			$this->directory_path = $Art_Store->directory_path;
			$this->directory_url  = $Art_Store->directory_url;
			$this->title          = __( 'Art Store Options', 'art-store' );
		}


		/**
		 * Run our hooks
		 */
		public function do_hooks() {
			if ( $this->meets_requirements() ) {
				add_action( 'admin_init', array( $this, 'init' ) );
				add_action( 'admin_menu', array( $this, 'add_options_page' ) );
				add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
			}
		}

		/**
		 * Register the WordPress setting
		 */
		public function init() {
			register_setting( $this->key, $this->key );
		}

		/**
		 * Add menu options page
		 */
		public function add_options_page() {
			$this->options_page = add_submenu_page( 'edit.php?post_type=art-store-work', $this->title, __( 'Options', 'art-store' ), 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		}

		/**
		 * Admin page markup. Mostly handled by CMB2
		 */
		public function admin_page_display() {
			?>
			<div class="wrap cmb2_options_page <?php echo $this->key; ?>">
				<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
				<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
			</div>
			<?php
		}

		/**
		 * Add the options metabox to the array of metaboxes
		 *
		 * @param  array $meta_boxes
		 * @return array $meta_boxes
		 */
		function add_options_page_metabox() {

		}

		/**
		 * Define the theme option metabox and field configuration
		 * This just returns in the sample snippet so may not be needed
		 */
		public function option_metabox() {
			return;
		}

	}

	$_GLOBALS['Art_Store_Options'] = new Art_Store_Options;
	$_GLOBALS['Art_Store_Options']->do_hooks();
}
