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
			}
		}

		/**
		 * Single product content filter
		 */
		public function product_single( $content ) {
			if ( is_singular( array( 'art-store-work' ) ) ) :
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
				echo wp_kses_post( $content );
				?>

				<div class="art-store-information product-information" id="art-work-<?php echo $post->ID; ?>">

					<dl>
						<?php
						// are we displaying the price and purchase info in the content?
						if ( 'content' == art_store_get_option( 'product_info' ) ) {

							// price
							if ( '' !== $product_information['price'] ) {
								$currency_symbol = ( art_store_get_option( 'currency_symbol' ) ) ? art_store_get_option( 'currency_symbol' ) : '$'; ?>
								<dt><?php _e( 'Price', 'art-store' ); ?></dt>
								<dd><?php echo $currency_symbol . esc_attr( $product_information['price'] );?></dd>
							<?php }
							// status, check if it's set to "enquire for price" and if a URL has been set for the enquire for price link
							if ( 'enquire' == $product_information['status'] && 'none' !== art_store_get_option( 'enquire_for_price' ) ) { ?>
								<dt></dt>
								<dd><a href="<?php echo get_permalink( absint( art_store_get_option( 'enquire_for_price' ) ) ); ?>"><?php _e( 'Enquire for Price', 'art-store' ); ?></a></dd>
							<?php } else {

								switch ( $product_information['status'] ) {

									// plain "enquire for price" text
									case 'enquire': ?>
										<dt><?php _e( 'Enquire for Price', 'art-store' ); ?></dt>
										<dd></dd>
										<?php break;

									case 'sold' : ?>
										<dt><?php _e( 'Sold', 'art-store' ); ?></dt>
										<dd></dd>
										<?php break;

									case 'nfs' : ?>
										<dt><?php _e( 'Not for Sale', 'art-store' ); ?></dt>
										<dd></dd>
										<?php break;

									case 'sale' : ?>
										<dt><?php _e( 'For Sale', 'art-store' ); ?></dt>
										<dd>
											<?php
											// are we using urls or code for the buy button?
											if ( 'url' == art_store_get_option( 'code_or_url' ) ) { ?>
												<a href="<?php echo esc_url( $product_information['btn_url'] ); ?>"><?php echo get_art_store_button_url(); ?></a>
											<?php } else {
												// if stuff is getting stripped out, it could be right here
												echo wp_kses_post( $product_information['btn_code'] );
											}?>
										</dd>
										<?php break;

									default:
										break;
								}

							}

						} ?>
					</dl>
				</div>
				<div class="art-store-meta  product-metainfo">
					<ul>
						<?php if ( get_art_store_product_terms( 'art-store-subject', $post->ID ) ) { ?>
							<li><?php the_art_store_themes(); ?></li>
						<?php } ?>
						<?php if ( get_art_store_product_terms( 'art-store-form', $post->ID ) ) { ?>
							<li><?php the_art_store_forms(); ?></li>
						<?php } ?>
						<?php if ( get_art_store_product_terms( 'art-store-medium', $post->ID ) ) { ?>
							<li><?php the_art_store_media(); ?></li>
						<?php } ?>
					</ul>
				</div>

				<?php

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
	}

	$_GLOBALS['Art_Store_Public'] = new Art_Store_Public;
	$_GLOBALS['Art_Store_Public']->do_hooks();
}