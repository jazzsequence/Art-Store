<?php
/**
 * CMB2 class
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Art_Store_CMB' ) ) {

	class Art_Store_CMB {

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			$this->basename       = art_store()->basename;
			$this->directory_path = art_store()->directory_path;
			$this->directory_url  = art_store()->directory_url;
			$this->prefix         = art_store()->prefix;

	    	/**
	    	 * Handle the meta boxes
	    	 */
			add_filter( 'cmb2_meta_boxes', array( $this, 'do_meta_boxes' ) );
		}

		/**
		 * Handle the CMB2 meta boxes
		 */
		public function do_meta_boxes( array $meta_boxes ) {

			$meta_boxes['art_work_details'] = array(
				'id'           => 'art-work-details',
				'title'        => __( 'Product Information', 'art-store' ),
				'object_types' => array( 'art-store-work' ),
				'context'      => 'normal',
				'show_names'   => true,
				'fields'       => array(
					'price' => array(
						'name'       => __( 'Price', 'art-store' ),
						'id'         => $this->prefix . 'price',
						'type'       => 'text_money',
						'desc'       => __( 'Item Price', 'art-store' )
					),
					'status' => array(
						'name'       => __( 'Status', 'art-store' ),
						'id'         => $this->prefix . 'status',
						'type'       => 'select',
						'default'    => 'sale',
						'options'    => array(
							'sale'    => __( 'For Sale', 'art-store' ),
							'enquire' => __( 'Enquire for Price', 'art-store' ),
							'sold'    => __( 'Sold', 'art-store' ),
							'nfs'     => __( 'Not for Sale', 'art-store' )
						)
					),
					'button_url' => array(
						'name'       => __( 'Button URL', 'art-store' ),
						'id'         => $this->prefix . 'button_url',
						'type'       => 'text_url',
						'desc'       => __( 'The URL to purchase', 'art-store' ),
						'show_on_cb' => array( $this, 'are_product_urls_active' )
					),
					'button_html' => array(
						'name'       => __( 'PayPal Button HTML', 'art-store' ),
						'id'         => $this->prefix . 'button_html',
						'type'       => 'textarea_code',
						'desc'       => __( 'Enter the PayPal button code for logged-out users and non-members.', 'art-store' ),
						'show_on_cb' => array( $this, 'are_html_codes_active' ),
						'attributes' => array( 'rows' => 5 )
					),
					'shipping_info' => array(
						'name'       => __( 'Shipping Information', 'art-store' ),
						'id'         => $this->prefix . 'shipping_info',
						'type'       => 'wysiwyg',
						'desc'       => __( '(Optional) Special shipping considerations, shipping method, weight, etc.', 'art-store' ),
						'options'    => array(
							'media_buttons' => false,
							'teeny'         => true,
							'textarea_rows' => 5,
						)
					),
					'width' => array(
						'name'       => __( 'Width', 'art-store' ),
						'id'         => $this->prefix . 'width',
						'type'       => 'text_small',
					),
					'height' => array(
						'name'       => __( 'Height', 'art-store' ),
						'id'         => $this->prefix . 'height',
						'type'       => 'text_small'
					),
					'depth' => array(
						'name'       => __( 'Depth', 'art-store' ),
						'id'         => $this->prefix . 'depth',
						'type'       => 'text_small'
					),
					'other_notes' => array(
						'name'       => __( 'Other Notes', 'art-store' ),
						'id'         => $this->prefix . 'other_notes',
						'type'       => 'wysiwyg',
						'desc'       => __( '(Optional) Any other information about the item.', 'art-store' ),
						'options'    => array(
							'media_buttons' => false,
							'teeny'         => true,
							'textarea_rows' => 5,
						)
					)
				)
			);

			$meta_boxes['art_forms'] = array(
				'id'           => 'art-forms',
				'title'        => __( 'Art Forms', 'art-store' ),
				'object_types' => array( 'art-store-work' ),
				'context'      => 'side',
				'priority'     => 'low',
				'show_names'   => false,
				'fields'       => array(
					array(
						'id'         => $this->prefix . 'art_forms',
						'type'              => 'taxonomy_multicheck',
						'taxonomy'          => 'art-store-form',
						'select_all_button' => false
					)
				)
			);

			$meta_boxes['medium'] = array(
				'id'           => 'medium',
				'title'        => __( 'Medium', 'art-store' ),
				'object_types' => array( 'art-store-work' ),
				'context'      => 'side',
				'priority'     => 'low',
				'show_names'   => false,
				'fields'       => array(
					array(
						'id'         => $this->prefix . 'medium',
						'type'              => 'taxonomy_multicheck',
						'taxonomy'          => 'art-store-medium',
						'select_all_button' => false
					)
				)
			);

			return $meta_boxes;
		}

		/**
		 * Callback function that checks if members setting is enabled
		 * Members options were removed, but leaving these functions for
		 * possible future use
		 */
		public function is_members_enabled() {
			// TODO check option setting for members. for now, just return true
			return false;
		}

		/**
		 * Callback function that checks if we're using URLs for product purchases or HTML codes
		 */
		public function are_product_urls_active() {
			$code_or_url = art_store_get_option( 'code_or_url' );

			if ( 'url' == $code_or_url ) {
				return true;
			} else {
				return false;
			}


		}

		/**
		 * Callback function that checks if we're using html codes for buttons
		 */
		public function are_html_codes_active() {
			// return whatever the opposite of are_product_urls_active is
			if ( $this->are_product_urls_active() ) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * Callback function that checks members and product urls/html codes settings to determine
		 * which members button boxes to display
		 * Members options were removed, but leaving these functions for
		 * possible future use
		 */
		public function members_and_html() {
			if ( $this->is_members_enabled() ) {
				if ( $this->are_html_codes_active() ) {
					return true;
				}
			}

			return false;
		}

	}

	$_GLOBALS['Art_Store_CMB'] = new Art_Store_CMB;
}
