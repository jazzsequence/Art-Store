<?php
/**
 * File that handles generating default taxonomy terms
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Art_Store_Terms' ) ) {

	class Art_Store_Terms {

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			$this->basename       = art_store()->basename;
			$this->directory_path = art_store()->directory_path;
			$this->directory_url  = art_store()->directory_url;
		}

		/**
		 * Register taxonomy terms
		 */
		public function do_hooks() {
			if ( $this->meets_requirements() ) {
				add_action( 'init', array( $this, 'insert_terms' ) );
			}
		}

		/**
		 * Insert the default taxonomy terms on init, if they don't already exist
		 */
		public function insert_terms() {
			// terms for art form
			wp_insert_term( 'Mixed Media', 'art-store-form', array(
				'slug' => 'mixed-media'
			) );
			wp_insert_term( 'Painting', 'art-store-form', array(
				'slug' => 'painting'
			) );
			wp_insert_term( 'Sculpture', 'art-store-form', array(
				'slug' => 'sculpture'
			) );
			wp_insert_term( 'Lithography/Printmaking', 'art-store-form', array(
				'slug' => 'printmaking'
			) );
			wp_insert_term( 'Sketch', 'art-store-form', array(
				'slug' => 'sketch'
			) );
			wp_insert_term( 'Art Print', 'art-store-form', array(
				'slug' => 'print'
			) );
		}

	}

	$_GLOBALS['Art_Store_Terms'] = new Art_Store_Terms;
	$_GLOBALS['Art_Store_Terms']->do_hooks();
}
