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

/**
 * Public function for getting a product's meta
 *
 * @return array 	An array of post meta for the current post
 */
function get_art_store_info( $post_id = 0 ) {
	// return false if no post id was passed
	if ( !$post_id )
		return false;

	$prefix = art_store()->prefix;

	$info['price'] = ( get_post_meta( $post_id, $prefix . 'price', true ) ) ? get_post_meta( $post_id, $prefix . 'price', true ) : '';
	$info['status'] = ( get_post_meta( $post_id, $prefix . 'status', true ) ) ? get_post_meta( $post_id, $prefix . 'status', true ) : 'sale';
	$info['btn_url'] = ( get_post_meta( $post_id, $prefix . 'button_url', true ) ) ? get_post_meta( $post_id, $prefix . 'button_url', true ) : '';
	$info['btn_code'] = ( get_post_meta( $post_id, $prefix . 'button_html', true ) ) ? get_post_meta( $post_id, $prefix . 'button_html', true ) : '';
	$info['shipping'] = ( get_post_meta( $post_id, $prefix . 'shipping_info', true ) ) ? get_post_meta( $post_id, $prefix . 'shipping_info', true ) : '';
	$info['width'] = ( get_post_meta( $post_id, $prefix . 'width', true ) ) ? get_post_meta( $post_id, $prefix . 'width', true ) : '';
	$info['height'] = ( get_post_meta( $post_id, $prefix . 'height', true ) ) ? get_post_meta( $post_id, $prefix . 'height', true ) : '';
	$info['depth'] = ( get_post_meta( $post_id, $prefix . 'depth', true ) ) ? get_post_meta( $post_id, $prefix . 'depth', true ) : '';
	$info['notes'] = ( get_post_meta( $post_id, $prefix . 'other_notes', true ) ) ? get_post_meta( $post_id, $prefix . 'other_notes', true ) : '';

	return $info;
}

/**
 * Public function for getting a product's taxonomy terms
 *
 * @param string $tax 		The taxonomy to get terms for
 * @param int    $post_id 	The id of the post to pull terms for
 */
function get_art_store_product_terms( $taxonomy = '', $post_id = 0 ) {
	// die if no tax was passed
	if ( '' == $taxonomy ) {
		wp_die( __( 'No taxonomy was passed to <code>get_art_store_product_terms</code>.', 'art-store' ) );
	}

	// if no post_id was passed, see if we can get it from the global and die if we can't
	if ( !$post_id ) {
		global $post;
		if ( $post->ID ) {
			$post_id = $post->ID;
		} else {
			wp_die( 'No post ID was passed to <code>get_art_store_product_terms</code> and could not identify post ID automagically.', 'art-store' );
		}
	}

	return wp_get_post_terms( $post_id, $taxonomy );

}