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
			if( !is_admin() ) {
				add_filter( 'the_content', array( $this, 'product_single' ), 20 );
				add_filter( 'the_excerpt', array( $this, 'product_excerpt' ), 20 );
			}
		}

		/**
		 * Product content filter
		 */
		public function product_single( $content ) {
			if ( is_singular( array( 'art-store-work' ) ) || is_post_type_archive( array( 'art-store-work' ) ) || 'art-store-work' == get_post_type( get_the_ID() ) ) :
				global $post;

				$product_information = get_art_store_info( $post->ID );

				ob_start();

				// display the featured image first
				if ( art_store_get_option( 'show_featured_image' ) ) {
					if ( has_post_thumbnail( $post->ID ) ) {
						echo get_the_post_thumbnail( $post->ID, 'large' );
					} else {
						// if there's no featured image, tell someone about it
						_e( 'No image set for this product.', 'art-store' );
					}
				}

				// display the post content
				echo $content;

				echo $this->product_information( $post->ID );

				return ob_get_clean();

			else :

				return $content;

			endif;
		}

		/**
		 * Excerpt filter for products
		 */
		public function product_excerpt( $content ) {
			if ( is_post_type_archive( array( 'art-store-work' ) ) || 'art-store-work' == get_post_type( get_the_ID() ) ) :

				global $post;

				ob_start();

				// display post content
				echo $content;

				// then display the product information
				echo $this->product_information( $post->ID );

				return ob_get_clean();

			else :

				return $content;

			endif;
		}

		/**
		 * Function to display the item status
		 *
		 * @param  string $status 	Status passed from the product meta
		 * @return string $output 	A string describing the status of the item
		 */
		public function display_status( $status = '' ) {
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

		/**
		 * Function to either return a linked button, the button code or nothing for a product
		 *
		 * @param  int    $post_id 	The id of the post
		 * @return string $output 	A hyperlinked image, html button code, or nothing if not for sale
		 */
		public function display_button( $post_id = 0 ) {
			// bail if no id was passed
			if ( 0 == $post_id ) {
				return;
			}

			// get the product information
			$product_information = get_art_store_info( $post_id );

			// bail if the product isn't foir sale
			if ( in_array( $product_information['status'], array( 'enquire', 'sold', 'nfs' ) ) ) {
				return;
			}

			$output = '';

			// are we using urls or code for the buy button?
			if ( 'url' == art_store_get_option( 'code_or_url' ) ) {

				$output = '<a href="' . esc_url( $product_information['btn_url'] ) . '">' . get_art_store_button_url() . '</a>';

			} else {

				// if stuff is getting stripped out, it could be right here
				$output = wp_kses_post( $product_information['btn_code'] );

			}

			return $output;

		}

		/**
		 * Output the product information.
		 *
		 * @param int     $post_id 	The id of the post to display information about
		 * @return string $output 	The html-formatted product information
		 */
		public function product_information( $post_id ) {
			// bail if no post ID was passed
			if ( 0 == $post_id ) {
				return;
			}

			$output = '';
			ob_start(); ?>

			<div class="art-store-information product-information" id="art-work-<?php echo $post_id; ?>">

				<dl>
					<?php
					// are we displaying the price and purchase info in the content?
					if ( 'content' == art_store_get_option( 'product_info' ) ) {

						the_art_store_product_information( $post_id );

					} ?>
				</dl>
			</div>
			<div class="art-store-meta  product-metainfo">
				<ul>
					<?php if ( get_art_store_product_terms( 'art-store-subject', $post_id ) ) { ?>
						<li><?php the_art_store_themes(); ?></li>
					<?php } ?>
					<?php if ( get_art_store_product_terms( 'art-store-form', $post_id ) ) { ?>
						<li><?php the_art_store_forms(); ?></li>
					<?php } ?>
					<?php if ( get_art_store_product_terms( 'art-store-medium', $post_id ) ) { ?>
						<li><?php the_art_store_media(); ?></li>
					<?php } ?>
				</ul>
			</div>

			<?php

			$output = ob_get_clean();

			return $output;
		}

	}

	$_GLOBALS['Art_Store_Public'] = new Art_Store_Public;
	$_GLOBALS['Art_Store_Public']->do_hooks();
}