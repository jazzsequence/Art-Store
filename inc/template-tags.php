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
function get_art_store_info( $post_id = 0, $field = '' ) {
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

	// if no field was passed, return the full array
	if ( '' == $field ) :
		return $info;

	// otherwise, return the single field requested
	else :
		// check that the field exists
		if ( isset( $info[$field] ) ) {

			return $info[$field];

		} else {

			return wp_die( sprintf( __( 'Incorrect parameter passed to %1$sget_art_store_info%2$s. Could not find a value for %3$s.', 'art-store' ), '<code>', '</code>', $field ), __( 'Art Store Error', 'art-store' ) );

		}

	endif;
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

/**
 * Public function for displaying a list of Subjects/Themes
 * Must be used inside the Loop
 *
 * @param string $before 	Text to display before the actual tags are displayed. Defaults to Subjects/Themes:
 * @param string $sep 		Text or character to display between each tag link. The default is a comma (,) between each tag.
 * @param string $after 	Text to display after the last tag. The default is to display nothing.
 */
function the_art_store_themes( $before = '', $sep = ', ', $after = '' ) {
	// if nothing is passed for the $before argument, add a localized string
	if ( '' == $before ) {
		$before = __( 'Subjects/Themes:', 'art-store' ) . ' ';
	}

	$terms = get_art_store_product_terms( 'art-store-subject' );

	if ( $terms ) :

		echo $before;

		$count = 1;
		foreach( $terms as $term ) {
			echo '<a href="' . get_term_link( $term, 'art-store-subject' ) . '" title="' . sprintf( __( 'Permanent link to %s', 'art-store' ), $term->name ) . '">' . $term->name . '</a>';
			if ( count( $terms ) > $count ) {
				echo $sep;
				$count++;
			}
		}

		echo $after;

	else :
		return;

	endif;
}

/**
 * Public function for displaying a list of Art Forms
 * Must be used inside the Loop
 *
 * @param string $before 	Text to display before the actual tags are displayed. Defaults to Art Form(s):
 * @param string $sep 		Text or character to display between each tag link. The default is a comma (,) between each tag.
 * @param string $after 	Text to display after the last tag. The default is to display nothing.
 */
function the_art_store_forms( $before = '', $sep = ', ', $after = '' ) {
	// if nothing is passed for the $before argument, add a localized string
	if ( '' == $before ) {
		$before = __( 'Art Form(s):', 'art-store' ) . ' ';
	}

	$terms = get_art_store_product_terms( 'art-store-form' );

	if ( $terms ) :

		echo $before;

		$count = 1;
		foreach( $terms as $term ) {
			echo '<a href="' . get_term_link( $term, 'art-store-form' ) . '" title="' . sprintf( __( 'Permanent link to %s', 'art-store' ), $term->name ) . '">' . $term->name . '</a>';
			if ( count( $terms ) > $count ) {
				echo $sep;
				$count++;
			}
		}

		echo $after;

	else :
		return;

	endif;
}

/**
 * Public function for displaying a list of artistic media
 * Must be used inside the Loop
 *
 * @param string $before 	Text to display before the actual tags are displayed. Defaults to Medium:
 * @param string $sep 		Text or character to display between each tag link. The default is a comma (,) between each tag.
 * @param string $after 	Text to display after the last tag. The default is to display nothing.
 */
function the_art_store_media( $before = '', $sep = ', ', $after = '' ) {
	// if nothing is passed for the $before argument, add a localized string
	if ( '' == $before ) {
		$before = __( 'Medium:', 'art-store' ) . ' ';
	}

	$terms = get_art_store_product_terms( 'art-store-medium' );

	if ( $terms ) :

		echo $before;

		$count = 1;
		foreach( $terms as $term ) {
			echo '<a href="' . get_term_link( $term, 'art-store-medium' ) . '" title="' . sprintf( __( 'Permanent link to %s', 'art-store' ), $term->name ) . '">' . $term->name . '</a>';
			if ( count( $terms ) > $count ) {
				echo $sep;
				$count++;
			}
		}

		echo $after;

	else :
		return;

	endif;
}

/**
 * Echoes the product information. Wrapper for product_information function in the Public class
 *
 * @param int     $post_id 	The id of the post to display information about
 * @param bool 	  $echo 	True to echo, false to return
 */
function the_art_store_product_information( $post_id = 0, $echo = true ) {

	// bail if no post id was passed
	if ( !$post_id )
		return;

	$product_information = get_art_store_info( $post_id );

	ob_start();

	// price
	if ( '' !== $product_information['price'] ) {
		$currency_symbol = ( art_store_get_option( 'currency_symbol' ) ) ? art_store_get_option( 'currency_symbol' ) : '$'; ?>
		<dt itemprop="price" class="price-label"><?php _e( 'Price', 'art-store' ); ?></dt>
		<dd><?php echo $currency_symbol . esc_attr( $product_information['price'] );?></dd>
	<?php }
	// status, check if it's set to "enquire for price" and if a URL has been set for the enquire for price link
	if ( 'enquire' == $product_information['status'] && 'none' !== art_store_get_option( 'enquire_for_price' ) ) { ?>

		<dt></dt>
		<dd class="enquire-for-price"><a href="<?php echo get_permalink( absint( art_store_get_option( 'enquire_for_price' ) ) ); ?>"><?php _e( 'Enquire for Price', 'art-store' ); ?></a></dd>

	<?php } else { ?>

		<dt class="status-label <?php echo $product_information['status']; ?>"><?php echo art_store_display_status( $product_information['status'] ); ?></dt>
		<dd class="product-button"><?php echo art_store_display_button( $post_id ); ?></dd>

	<?php }

	// shipping info
	if ( isset( $product_information['shipping'] ) ) { ?>

		<dt class="shipping-label"><?php _e( 'Shipping Information', 'art-store' ); ?></dt>
		<dd class="shipping-info"><?php echo esc_html( $product_information['shipping'] ); ?></dd>
	<?php }

	// dimensions

	if ( isset( $product_information['width'] ) ) { ?>

		<dt class="width-label"><?php _e( 'Width', 'art-store' ); ?></dt>
		<dd class="width"><?php echo esc_html( $product_information['width'] ); ?>

	<?php }

	if ( isset( $product_information['height'] ) ) { ?>

		<dt class="height-label"><?php _e( 'Height', 'art-store' ); ?></dt>
		<dd class="height"><?php echo esc_html( $product_information['height'] ); ?></dd>

	<?php }

	if ( isset( $product_information['depth'] ) ) { ?>

		<dt class="depth-label"><?php _e( 'Depth', 'art-store' ); ?></dt>
		<dd class="depth"><?php echo esc_html( $product_information['depth'] ); ?></dd>
	<?php }

	// other information
	if ( isset( $product_information['notes'] ) ) { ?>

		<dt class="notes-label"><?php _e( 'Other information', 'art-store' ); ?></dt>
		<dd class="other-info"><?php echo wp_kses_post( $product_information['notes'] ); ?></dd>
	<?php }

	$output = ob_get_clean();

	// echo if echo is true
	if ( $echo ) :

		echo $output;

	// otherwise return
	else :

		return $output;

	endif;
}

/**
 * Function to display the item status
 *
 * @param  string $status 	Status passed from the product meta
 * @return string $output 	A string describing the status of the item
 */
function art_store_display_status( $status = '' ) {
	if ( '' == $status ) {
		$output = __( 'No status found', 'art-store' );
	}

	switch( $status ) {

		// plain "enquire for price" text
		case 'enquire':
			$output = __( 'Enquire for Price', 'art-store' );
			break;

		case 'sold' :
			$output = __( 'Sold', 'art-store' );
			break;

		case 'nfs' :
			$output = __( 'Not for Sale', 'art-store' );
			break;

		case 'sale' :
			$output = __( 'For Sale', 'art-store' );
			break;

		default :
			break;
	}

	return $output;

}