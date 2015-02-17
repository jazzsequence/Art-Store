<?php
/**
 * Plugin Name: Art Store
 * Plugin URI: http://webdevstudios.com
 * Description: Art Store allows a artists to sell their work on their own WordPress website using PayPal. It creates a gallery with all of the meta information for each artwork and displays them in a nice horizontal slider, as a thumbnail page, or in a widget.
 * Author: WebDevStudios
 * Author URI: http://webdevstudios.com
 * Version: 1.0.0
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
     * @since 1.0.0
     */
    class Art_Store {

        /**
         * Construct function to get things started
         *
         * @since 1.0.0
         */
        public function __construct() {

	        /**
	         * Setup some base variables for the plugin
	         */
	        $this->basename       = plugin_basename( __FILE__ );
	        $this->directory_path = plugin_dir_path( __FILE__ );
	        $this->directory_url  = plugins_url( dirname( $this->basename ) );

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
	     * @since 1.0.0
	     */
	    public function includes() {

	    	require_once( $this->directory_path . '/inc/cpt_core/CPT_Core.php' );
	    	require_once( $this->directory_path . '/inc/taxonomy_core/Taxonomy_Core.php' );
	    	require_once( $this->directory_path . '/inc/cmb2/bootstrap.php' );

	    } /* includes() */

	    /**
	     * Register CPTs & taxonomies
	     *
	     * @since 1.0.0
	     */
	    public function do_hooks() {

		    if( $this->meets_requirements() ) {
			    add_action( 'init', array( $this, 'register_post_types' ) );
			    add_action( 'init', array( $this, 'register_taxonomies' ) );
		    }

	    } /* do_hooks() */

	    /**
	     * Activation hook for the plugin.
	     *
	     * @since 1.0.0
	     */
	    public function activate() {

		    // If BadgeOS is available, run our activation functions
		    if ( $this->meets_requirements() ) {
		    }

	    } /* activate() */

	    /**
	     * Deactivation hook for the plugin.
	     *
	     * @since 1.0.0
	     */
	    public function deactivate() {

	    } /* deactivate() */

	    /**
	     * Check that all plugin requirements are met
	     *
	     * @since  1.0.0
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
	     * @since 1.0.0
	     */
	    public function maybe_disable_plugin() {

		    if ( ! $this->meets_requirements() ) {
			    // Display our error
			    echo '<div id="message" class="error">';
			    echo '<p>' . sprintf( __( 'Sample plugin is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'art-store' ), admin_url( 'plugins.php' ) ) . '</p>';
			    echo '</div>';

			    // Deactivate our plugin
			    deactivate_plugins( $this->basename );
		    }

	    } /* maybe_disable_plugin() */

	    /**
	     * Registers our custom post-types
	     *
	     * @since 1.0.0
	     */
	    public function register_post_types() {
		    register_via_cpt_core(
			    array(
				    __( 'Sample', 'art-store' ),  // Single
				    __( 'Samples', 'art-store' ), // Plural
				    'art-store-samples'           // Registered slug
			    ),
			    array(
				    'menu_icon' => 'dashicons-smiley' // Choose a custom dashicon (http://melchoyce.github.io/dashicons/) or pass an image URI
			    ) // register_post_type args
		    );
	    } /* register_post_types() */

	    /**
	     * Registers our custom taxonomies
	     *
	     * @since 1.0.0
	     */
	    public function register_taxonomies() {
		    register_via_taxonomy_core(
			    array(
				    __( 'Sample Tag', 'art-store' ),  // Single
				    __( 'Sample Tags', 'art-store' ), // Plural
				    'art-store-sample-tags'           // Registered slug
			    ),
			    array(
				    'hierarchical' => false
			    ), // register_taxonomy args
			    array(
				    'art-store-samples'
			    ) // post-types
		    );
	    } /* register_taxonomies() */

    }

    $_GLOBALS['Art_Store'] = new Art_Store;
	$_GLOBALS['Art_Store']->do_hooks();
}
