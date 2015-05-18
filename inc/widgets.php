<?php
/**
 * Art Store widgets
 */

// Exit if accessed directly
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Information Widget
 */

class Art_Store_Product_Widget extends WP_Widget {


	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 */
	protected $widget_slug = 'art-store-product';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 */
	protected $default_widget_title = '';


	/**
	 * Shortcode name for this widget
	 *
	 * @var string
	 */
	protected static $shortcode = 'art_store_product';


	/**
	 * Contruct widget.
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( '(Art Store) Single Product', 'art-store' );
		$this->default_widget_title = esc_html__( 'Product Information', 'art-store' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'Displays product information about the currently-displayed art piece.', 'art-store' ),
			)
		);

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}


	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @param  array  $args      The widget arguments set up when a sidebar is registered.
	 * @param  array  $instance  The widget settings as set by user.
	 */
	public function widget( $args, $instance ) {

		echo self::get_widget( array(
			'before_widget'  => $args['before_widget'],
			'after_widget'   => $args['after_widget'],
			'before_title'   => $args['before_title'],
			'after_title'    => $args['after_title'],
			'title'          => $instance['title'],
			// these aren't used but maybe will in a future revision
			'price_label'    => $instance['price_label'],
			'shipping_label' => $instance['shipping_label'],
			'width_label'    => $instance['width_label'],
			'height_label'   => $instance['height_label'],
			'depth_label'    => $instance['depth_label'],
			'notes_label'    => $instance['notes_label'],
		) );

	}


	/**
	 * Return the widget/shortcode output
	 *
	 * @param  array  $atts Array of widget/shortcode attributes/args
	 * @return string       Widget output
	 */
	public static function get_widget( $atts ) {
		global $wp_query;
		$post = $wp_query->post;

		$widget = '';

		// Set up default values for attributes
		$atts = shortcode_atts(
			array(
				// Ensure variables
				'before_widget'  => '',
				'after_widget'   => '',
				'before_title'   => '',
				'after_title'    => '',
				'title'          => '',
				// these aren't used but maybe will in a future revision
				'price_label'    => __( 'Price', 'art-store' ),
				'shipping_label' => __( 'Shipping Information', 'art-store' ),
				'width_label'    => __( 'Width', 'art-store' ),
				'height_label'   => __( 'Height', 'art-store' ),
				'depth_label'    => __( 'Depth', 'art-store' ),
				'notes_label'    => __( 'Notes', 'art-store' ),
			),
			(array) $atts,
			self::$shortcode
		);

		// Before widget hook
		$widget .= $atts['before_widget'];

		// Title
		$widget .= ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		$widget .= the_art_store_product_information( $post->ID, false );

		// After widget hook
		$widget .= $atts['after_widget'];

		// if we've decided to display product info in the main content or if the post type isn't art-store-work
		if ( 'content' == art_store_get_option( 'product_info' ) || 'art-store-work' !== $post->post_type ) {

			return;

		} else {

			return $widget;

		}
	}


	/**
	 * Update form values as they are saved.
	 *
	 * @param  array  $new_instance  New settings for this instance as input by the user.
	 * @param  array  $old_instance  Old settings for this instance.
	 * @return array  Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values
		$instance = $old_instance;

		// Sanitize title before saving to database
		$instance['title']          = sanitize_text_field( $new_instance['title'] );
		// these aren't used but maybe will in a future revision
		$instance['price_label']    = sanitize_text_field( $new_instance['price_label'] );
		$instance['shipping_label'] = sanitize_text_field( $new_instance['shipping_label'] );
		$instance['width_label']    = sanitize_text_field( $new_instance['width_label'] );
		$instance['height_label']   = sanitize_text_field( $new_instance['height_label'] );
		$instance['depth_label']    = sanitize_text_field( $new_instance['depth_label'] );
		$instance['notes_label']    = sanitize_text_field( $new_instance['notes_label'] );



		// Flush cache
		$this->flush_widget_cache();

		return $instance;
	}


	/**
	 * Back-end widget form with defaults.
	 *
	 * @param  array  $instance  Current settings.
	 */
	public function form( $instance ) {

		// If there are no settings, set up defaults
		$instance = wp_parse_args( (array) $instance,
			array(
				'title'          => $this->default_widget_title,
				// these aren't used but maybe will in a future revision
				'price_label'    => __( 'Price', 'art-store' ),
				'shipping_label' => __( 'Shipping Information', 'art-store' ),
				'width_label'    => __( 'Width', 'art-store' ),
				'height_label'   => __( 'Height', 'art-store' ),
				'depth_label'    => __( 'Depth', 'art-store' ),
				'notes_label'    => __( 'Other Information', 'art-store' )
			)
		);

		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'art-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" /></p>

		<?php
		// these aren't used but maybe will in a future revision
		/*
		<?php
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'price_label' ) ); ?>"><?php esc_html_e( 'Price Label:', 'art-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'price_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'price_label' ) ); ?>" type="text" value="<?php echo esc_html( $instance['price_label'] ); ?>" placeholder="optional" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'width_label' ) ); ?>"><?php esc_html_e( 'Width Label:', 'art-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width_label' ) ); ?>" type="text" value="<?php echo esc_html( $instance['width_label'] ); ?>" placeholder="optional" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'height_label' ) ); ?>"><?php esc_html_e( 'Height Label:', 'art-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height_label' ) ); ?>" type="text" value="<?php echo esc_html( $instance['height_label'] ); ?>" placeholder="optional" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'depth_label' ) ); ?>"><?php esc_html_e( 'Depth Label:', 'art-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'depth_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'depth_label' ) ); ?>" type="text" value="<?php echo esc_html( $instance['depth_label'] ); ?>" placeholder="optional" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'notes_label' ) ); ?>"><?php esc_html_e( 'Other Information Label:', 'art-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'notes_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'notes_label' ) ); ?>" type="text" value="<?php echo esc_html( $instance['notes_label'] ); ?>" placeholder="optional" /></p>

		<?php
		*/

	}
}


/**
 * Related Products Widget
 */

class Art_Store_Related_Products extends WP_Widget {


	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 */
	protected $widget_slug = 'art-store-related-products';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 */
	protected $default_widget_title = '';


	/**
	 * Shortcode name for this widget
	 *
	 * @var string
	 */
	protected static $shortcode = 'art_store_related_products';


	/**
	 * Contruct widget.
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( '(Art Store) Related Products', 'art-store' );
		$this->default_widget_title = esc_html__( 'Related Products', 'art-store' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'Displays a list of products related to the current product.', 'art-store' ),
			)
		);

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}


	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @param  array  $args      The widget arguments set up when a sidebar is registered.
	 * @param  array  $instance  The widget settings as set by user.
	 */
	public function widget( $args, $instance ) {

		echo self::get_widget( array(
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => $instance['title'],
		) );

	}


	/**
	 * Return the widget/shortcode output
	 *
	 * @param  array  $atts Array of widget/shortcode attributes/args
	 * @return string       Widget output
	 */
	public static function get_widget( $atts ) {
		global $wp_query;
		$widget = '';

		// Set up default values for attributes
		$atts = shortcode_atts(
			array(
				// Ensure variables
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
				'title'         => '',
			),
			(array) $atts,
			self::$shortcode
		);

		// don't display the widget if there are no related products
		if ( ! art_store_get_related_product_ids() ) {
			return;
		}

		$posts = art_store_get_related_product_ids();

		// Before widget hook
		$widget .= $atts['before_widget'];

		// Title
		$widget .= ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		$widget .= '<ul class="art-store-related-products">';

		foreach( $posts as $post_id ) {
			$widget .= sprintf( '<li><a href="%1$s" title="%2$s">%2$s</a></li>', get_permalink( $post_id ), get_the_title( $post_id ) );
		}

		$widget .= '</ul>';

		// After widget hook
		$widget .= $atts['after_widget'];

		if ( 'art-store-work' == $wp_query->post->post_type ) {

			return $widget;

		}
	}


	/**
	 * Update form values as they are saved.
	 *
	 * @param  array  $new_instance  New settings for this instance as input by the user.
	 * @param  array  $old_instance  Old settings for this instance.
	 * @return array  Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values
		$instance = $old_instance;

		// Sanitize title before saving to database
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Sanitize text before saving to database
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = force_balance_tags( $new_instance['text'] );
		} else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) );
		}

		// Flush cache
		$this->flush_widget_cache();

		return $instance;
	}


	/**
	 * Back-end widget form with defaults.
	 *
	 * @param  array  $instance  Current settings.
	 */
	public function form( $instance ) {

		// If there are no settings, set up defaults
		$instance = wp_parse_args( (array) $instance,
			array(
				'title' => $this->default_widget_title,
			)
		);

		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'art-store' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" /></p>

		<?php
	}
}


/**
 * Register widgets with WordPress.
 */
function register_art_store_widgets() {
	register_widget( 'Art_Store_Related_Products' );
	register_widget( 'Art_Store_Product_Widget' );
}
add_action( 'widgets_init', 'register_art_store_widgets' );