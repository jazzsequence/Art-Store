<?php
/**
 * Public template tags for the plugin go here
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generic art store class wrapper function
 *
 * @return object
 */
function art_store() {
	return new Art_Store;
}

/**
 * Public function for getting the button image
 *
 * @return string 	URL to custom button image
 */
function get_art_store_button_url() {
	return art_store_get_option( 'button_image' );
}

/**
 * Public function for getting the art store gallery URL
 *
 * @return mixed 	URL to gallery page, false if none is selected
 */
function get_art_store_gallery_url() {
	if ( 'none' !== art_store_get_option( 'gallery_page' ) ) {
		$id = absint( art_store_get_option( 'gallery_page' ) );
		return get_permalink( $id );
	} else {
		return false;
	}
}

/**
 * Public function for getting the enquire for price URL
 *
 * @return mixed 	URL to enquire page or false if none is selected
 */
function get_art_store_enquire_url() {
	if ( 'none' !== art_store_get_option( 'enquire_for_price' ) ) {
		$id = art_store_get_option( 'enquire_for_price' );
		return get_permalink( $id );
	} else {
		return false;
	}
}