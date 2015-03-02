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
			$this->basename       = art_store()->basename;
			$this->directory_path = art_store()->directory_path;
			$this->directory_url  = art_store()->directory_url;
		}

		/**
		 * Run our hooks
		 */
		public function do_hooks() {

		}

	}

	$_GLOBALS['Art_Store_Public'] = new Art_Store_Public;
	$_GLOBALS['Art_Store_Public']->do_hooks();
}