<?php
/**
 * Class that handles generating default taxonomy terms
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
			add_action( 'init', array( $this, 'insert_terms' ) );
			add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ) );
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

			// insert terms for medium
			wp_insert_term( 'Acrylic', 'art-store-medium', array(
				'slug' => 'acrylic'
			) );
			wp_insert_term( 'Oil', 'art-store-medium', array(
				'slug' => 'oil'
			) );
			wp_insert_term( 'Pastel', 'art-store-medium', array(
				'slug' => 'pastel'
			) );
			wp_insert_term( 'Charcoal', 'art-store-medium', array(
				'slug' => 'charchoal'
			) );
			wp_insert_term( 'Watercolor', 'art-store-medium', array(
				'slug' => 'watercolor'
			) );
			wp_insert_term( 'Graphite', 'art-store-medium', array(
				'slug' => 'graphite'
			) );
			wp_insert_term( 'Mixed Media', 'art-store-medium', array(
				'slug' => 'mixed'
			) );
		}

		public function remove_meta_boxes() {
			remove_meta_box( 'tagsdiv-art-store-form', 'art-store-work', 'side' );
			remove_meta_box( 'tagsdiv-art-store-medium', 'art-store-work', 'side' );
		}

	}

	$_GLOBALS['Art_Store_Terms'] = new Art_Store_Terms;
}