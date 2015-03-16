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
			$this->basename       = art_store()->basename;
			$this->directory_path = art_store()->directory_path;
			$this->directory_url  = art_store()->directory_url;
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
				'options' => $this->get_all_pages(),
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
				'options' => $this->get_all_pages(),
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

			$cmb->add_field( array(
				'name'    => __( 'Single Product Purchase Information', 'art-store' ),
				'desc'    => __( 'Defines whether to use the Product Information widget to display purchase information (like the Buy Now button) or to display that in the main content area.', 'art-store' ),
				'id'      => 'product_info',
				'type'    => 'select',
				'options' => array(
					'widget'  => __( 'Widget', 'art-store' ),
					'content' => __( 'Content Area', 'art-store' )
				),
				'default' => 'content'
			) );

			$cmb->add_field( array(
				'name'    => __( 'Currency Symbol', 'art-store' ),
				'desc'    => __( 'Please enter the  symbol for the currency you will be selling items in.', 'art-store' ),
				'id'      => 'currency_symbol',
				'type'    => 'text_small',
				'default' => '$'
			) );

			$cmb->add_field( array(
				'name'    => __( 'Show Featured Image', 'art-store' ),
				'desc'    => __( 'If enabled, automatically displays the featured image in the single product page. Some themes might already do this for you, which would result in the image displaying twice. The default is not to show the image.', 'art-store' ),
				'id'      => 'show_featured_image',
				'type'    => 'select',
				'options' => array(
					false => __( 'Disabled (do not show the featured image)', 'art-store' ),
					true  => __( 'Enabled (automatically embed the featured image)', 'art-store' )
				),
				'default' => false
			) );

		}

		/**
		 * Define the theme option metabox and field configuration
		 * This just returns in the sample snippet so may not be needed
		 */
		public function option_metabox() {
			return;
		}

		/**
		 * Public getter method for retrieving protected/private variables
		 *
		 * @param  string  $field Field to retrieve
		 * @return mixed          Field value or exception is thrown
		 */
		public function __get( $field ) {
			// Allowed fields to retrieve
			if ( in_array( $field, array( 'key', 'metabox_id', 'fields', 'title', 'options_page' ), true ) ) {
				return $this->{$field};
			}

			throw new Exception( 'Invalid property: ' . $field );
		}

		/**
		 * Get a list of pages
		 *
		 * @return array 	An array of pages on the site
		 */
		public function get_all_pages() {
			$pages = get_pages();
			$list = array();

			$list['none'] = __( '- None -', 'art-store' );

			foreach( $pages as $page ) {
				$list[$page->ID] = $page->post_title;
			}

			return $list;
		}

	}

	$_GLOBALS['Art_Store_Options'] = new Art_Store_Options;
	$_GLOBALS['Art_Store_Options']->do_hooks();
}

/**
 * Helper function to get/return the myprefix_Admin object
 *
 * @return Art_Store_Options object
 */
function art_store_options() {
	$Art_Store_Options = new Art_Store_Options;
	return $Art_Store_Options;
}

/**
 * Wrapper function around cmb2_get_option
 *
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function art_store_get_option( $key = '' ) {
	return cmb2_get_option( art_store_options()->key, $key );
}