<?php
/**
 * Plugin Name: Art Store
 * Plugin URI: http://wpartstore.com
 * Description: Art Store allows artists to sell their work on their own WordPress website using PayPal. It creates a gallery with all of the meta information for each artwork and displays them in a nice horizontal slider, as a thumbnail page, or in a widget.
 * Author: jazzsequence, suzettefranck
 * Author URI: http://wpartstore.com
 * Version: 0.9.1
 * License: GPLv2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if wp-content exists, only run code if it does not
if ( ! class_exists( 'Art_Store' ) ) {

	/**
	 * Main Art Store Class
	 *
	 * @since 0.5.0
	 */
	class Art_Store {

		/**
		 * Construct function to get things started
		 *
		 * @since 0.5.0
		 */
		public function __construct() {

			/**
			 * Setup some base variables for the plugin
			 */
			$this->basename       = plugin_basename( __FILE__ );
			$this->directory_path = plugin_dir_path( __FILE__ );
			$this->directory_url  = plugins_url( dirname( $this->basename ) );
			$this->prefix         = '_wds_as_';

			/**
			 * Load external libraries
			 */
	    	require_once( $this->directory_path . '/inc/cpt_core/CPT_Core.php' );
	    	require_once( $this->directory_path . '/inc/taxonomy_core/Taxonomy_Core.php' );
	    	require_once( $this->directory_path . '/inc/cmb2/init.php' );

	    	/**
	    	 * Insert default taxonomy terms
	    	 */
			require_once( $this->directory_path . '/inc/terms.php' );

			/**
			 * Register widgets
			 */
			require_once( $this->directory_path . '/inc/widgets.php' );

			/**
			 * Include any required files
			 */
			add_action( 'init', array( $this, 'includes' ) );

			/**
			 * Load Textdomain
			 */
			load_plugin_textdomain( 'art-store', false, dirname( $this->basename ) . '/languages' );

			/**
			 * Activation/Deactivation Hooks
			 */
			register_activation_hook(   __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			/**
			 * Make sure we have our requirements, and disable the plugin if we do not have them.
			 */
			add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );

		}


		/**
		 * Include our plugin dependencies
		 *
		 * @since 0.5.0
		 */
		public function includes() {

			if ( $this->meets_requirements() ) {
				require_once( $this->directory_path . '/inc/template-tags.php' );
				require_once( $this->directory_path . '/inc/options.php' );
				require_once( $this->directory_path . '/inc/cmb.php' );
				require_once( $this->directory_path . '/inc/public.php' );
			}

		} /* includes() */

		/**
		 * Register CPTs & taxonomies
		 *
		 * @since 0.5.0
		 */
		public function do_hooks() {

			if( $this->meets_requirements() ) {
				add_action( 'init', array( $this, 'register_post_types' ), 9 );
				add_action( 'init', array( $this, 'register_taxonomies' ), 4 );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			}

		} /* do_hooks() */

		/**
		 * Activation hook for the plugin.
		 *
		 * @since 0.5.0
		 */
		public function activate() {

			if ( $this->meets_requirements() ) {
				// flush rewrites on activation
				flush_rewrite_rules();
			}

		} /* activate() */

		/**
		 * Deactivation hook for the plugin.
		 *
		 * @since 0.5.0
		 */
		public function deactivate() {

				// flush rewrites on deactivation
				flush_rewrite_rules();

		} /* deactivate() */

		/**
		 * Check that all plugin requirements are met
		 *
		 * @since  0.5.0
		 *
		 * @return bool
		 */
		public static function meets_requirements() {

			/**
			 * any plugin dependencies go here.
			 * everything we need is bundled in the plugin so we should be good
			 */

			/**
			 * We have met all requirements
			 */
			return true;
		} /* meets_requirements() */

		/**
		 * Check if the plugin meets requirements and disable it if they are not present.
		 *
		 * @since 0.5.0
		 */
		public function maybe_disable_plugin() {

			if ( ! $this->meets_requirements() ) {
				// Display our error
				echo '<div id="message" class="error">';
				echo '<p>' . sprintf( __( 'Art Store is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'art-store' ), admin_url( 'plugins.php' ) ) . '</p>';
				echo '</div>';

				// Deactivate our plugin
				deactivate_plugins( $this->basename );
			}

		} /* maybe_disable_plugin() */

		/**
		 * Registers our custom post-types
		 *
		 * @since 0.5.0
		 */
		public function register_post_types() {
			// label overrides go here
			$labels = array(
				'menu_name' => __( 'Art Store', 'art-store' )
			);

			register_via_cpt_core(
				array(
					__( 'Work', 'art-store' ),  // Single
					__( 'Works', 'art-store' ), // Plural
					'art-store-work'           // Registered slug
				),
				array(
					'menu_icon' => 'dashicons-art',
					'rewrite'   => array( 'slug' => 'work' ),
					'labels'    => $labels,
					'supports'     => array(
						'title',
						'editor',
						'thumbnail'
					)
				) // register_post_type args
			);
		} /* register_post_types() */

		/**
		 * Registers our custom taxonomies
		 *
		 * @since 0.5.0
		 */
		public function register_taxonomies() {

			// Register "Form" taxonomy
			register_via_taxonomy_core(
				array(
					__( 'Art Form', 'art-store' ),  // Single
					__( 'Art Forms', 'art-store' ), // Plural
					'art-store-form'           // Registered slug
				),
				array(
					'hierarchical' => false,
					'rewrite'      => array( 'slug' => 'form' )
				), // register_taxonomy args
				array(
					'art-store-work'
				) // post-types
			);

			// Register "Theme/Subject" taxonomy
			register_via_taxonomy_core(
				array(
					__( 'Theme/Subject', 'art-store' ),  // Single
					__( 'Themes/Subjects', 'art-store' ), // Plural
					'art-store-subject'           // Registered slug
				),
				array(
					'hierarchical' => false,
					'rewrite'      => array( 'slug' => 'subject' ),
					'labels'       => array(
						'separate_items_with_commas' => __( 'Separate subjects or themes with commas', 'art-store' ),
						'choose_from_most_used'      => __( 'Choose from most used themes and subjects', 'art-store' )
					)
				), // register_taxonomy args
				array(
					'art-store-work'
				) // post-types
			);

			// Register the "Medium" taxonomy
			register_via_taxonomy_core(
				array(
					__( 'Medium', 'art-store' ), // Single
					__( 'Media', 'art-store' ), // Plural
					'art-store-medium'
				),
				array(
					'hierarchical' => false,
					'rewrite'      => array( 'slug' => 'medium' )
				),
				array(
					'art-store-work'
				)
			);

		} /* register_taxonomies() */

		/**
		 * Enqueue the public facing javascript for the cool slider
		 */
		public function enqueue_scripts() {
			// load our js if we aren't in the admin
			// also check if an option for page is set for the scroller or if no page was defined
			$gallery_page = art_store_get_option( 'gallery_home' );

			if ( !is_admin() && ( is_page( $gallery_page ) || 'none' == $gallery_page ) ) {
				wp_enqueue_script( 'jquery-ui' );
				wp_enqueue_script( 'jquery-ui-widget' );
				wp_enqueue_script( 'kinetic', $this->directory_url . '/assets/js/jquery.kinetic.min.js', array( 'jquery' ), '1.8.2', true );
				wp_enqueue_script( 'mousewheel', $this->directory_url . '/assets/js/jquery.mousewheel.min.js', array( 'jquery' ), '3.1.4', true );
				wp_enqueue_script( 'smoothdivscroll', $this->directory_url . '/assets/js/jquery.smoothdivscroll-1.3-min.js', array( 'jquery', 'kinetic', 'mousewheel' ), '1.3', true );
			}
		}

		/**
		 * Enqueue the public facing css
		 */
		public function enqueue_styles() {

			if ( !is_admin() ) {
				wp_enqueue_style( 'art-store', $this->directory_url . '/assets/css/art-store.css' );
			}

		}

	}

	$_GLOBALS['Art_Store'] = new Art_Store;
	$_GLOBALS['Art_Store']->do_hooks();
}
