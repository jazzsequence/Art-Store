<?php
/**
 * This class handles all the public facing aspects of the plugin
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Art_Store_Public' ) ) {

	class Art_Store_Public {

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			// TODO this stuff should probably be pulled from the parent class or just removed
			// $this->basename       = plugin_basename( __FILE__ );
			// $this->directory_path = plugin_dir_path( __FILE__ );
			// $this->directory_url  = plugins_url( dirname( $this->basename ) );
		}

		/**
		 * Run our hooks
		 */
		public function do_hooks() {
			if ( $this->meets_requirements() ) {
			}
		}

	}

	$_GLOBALS['Art_Store_Public'] = new Art_Store_Public;
	$_GLOBALS['Art_Store_Public']->do_hooks();
}