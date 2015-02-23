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
			add_action( 'admin_init', array( $this, 'init' ) );
			add_action( 'admin_menu', array( $this, 'add_options_page' ) );
			add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
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

			$cmb = new_cmb2_box( array(
				'id'      => $this->metabox_id,
				'hookup'  => false,
				'show_on' => array(
					'key'   => 'options-page',
					'value' => array( $this->key )
				)
			) );

			// create the cmb2 fields

			$cmb->add_field( array(
				'name'    => __( 'Gallery Home', 'art-store' ),
				'desc'    => __( 'The page you are displaying your artwork on.', 'art-store' ),
				'id'      => 'gallery_page',
				'type'    => 'select',
				'options' => array(), // TODO array for pages
				'default' => 'none'
			) );

			$cmb->add_field( array(
				'name'    => __( 'Button Image', 'art-store' ),
				'desc'    => __( 'Set a unique button image instead of the default PayPal (or other third-party payment processor) buy now button.', 'art-store' ),
				'id'      => 'button_image',
				'type'    => 'file'
			) );

			$cmb->add_field( array(
				'name'    => __( 'Enquire for price page', 'art-store' ),
				'desc'    => __( 'If a price is left blank, a page may be set for a contact form for visitors to enquire for a price of an item.', 'art-store' ),
				'id'      => 'enquire_for_price',
				'type'    => 'select',
				'options' => array(), // TODO array for pages
				'default' => 'none'
			) );

			$cmb->add_field( array(
				'name'    => __( 'HTML Code or URL', 'art-store' ),
				'desc'    => __( 'Choose whether to use HTML button codes copied from PayPal (or other third-party payment processor) or a direct URL to the payment page.', 'art-store' ),
				'id'      => 'code_or_url',
				'type'    => 'select',
				'options' => array(
					'code' => __( 'HTML Code', 'art-store' ),
					'url'  => __( 'URL', 'art-store' )
				),
				'default' => 'code'
			) );

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
