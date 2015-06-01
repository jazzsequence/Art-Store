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
			add_shortcode( 'art-store-gallery', array( $this, 'shortcode' ) );
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

		/**
		 * Add the side-scrolling gallery shortcode
		 */
		public function shortcode( $atts ) {
			extract( shortcode_atts( array(
				'width'  => null,
				'height' => null,
				'posts'  => -1,
				), $atts ) );

			$width  = ( isset( $atts['width'] ) )  ? $atts['width']  : 999;
			$height = ( isset( $atts['height'] ) ) ? $atts['height'] : 999;
			$posts  = ( isset( $atts['posts'] ) )  ? $atts['posts']  : -1;

			$art_store = art_store_works( array( 'posts_per_page' => $posts ) ); ?>

			<script type="text/javascript">
				jQuery(document).ready(function ($) {
					$("#makeMeScrollable").smoothDivScroll({
						mousewheelScrolling: false,
						manualContinuousScrolling: true
					});
				});
			</script>
			<div id="makeMeScrollable">
				<div class="scrollableArea">

					<?php if ( $art_store->have_posts() ) : while ( $art_store->have_posts() ) : $art_store->the_post(); ?>

						<section class="art-store-work product" id="art-store-work-<?php the_ID(); ?>">
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail( array( $width, $height ) ); ?></a>
						</section>

					<?php endwhile; endif; ?>

				</div> <!-- scrollableArea -->
			</div> <!-- makeMeScrollable -->

			<?php wp_reset_query();

		}

	}

	$_GLOBALS['Art_Store_Public'] = new Art_Store_Public;
	$_GLOBALS['Art_Store_Public']->do_hooks();
}