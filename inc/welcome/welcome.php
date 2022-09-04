<?php
/**
 * Welcome
 *
 * @package Obulma
 */

/**
 * Register welcome page.
 *
 * @since 1.0.0
 */
function obulma_add_welcome_menu() {
	add_theme_page( esc_html__( 'Obulma', 'obulma' ), esc_html__( 'Obulma', 'obulma' ), 'edit_theme_options', 'obulma-welcome', 'obulma_render_welcome_page' );
}

add_action( 'admin_menu', 'obulma_add_welcome_menu' );

function obulma_render_welcome_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$theme_data = wp_get_theme( 'obulma' );

	$theme_uri = $theme_data->get('ThemeURI');
	?>

	<div class="wrap ns-wrap">
		<div class="ns-header">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<?php /* translators: %s: version. */ ?>
			<p class="about-text"><?php echo sprintf( esc_html__( 'Version: %s', 'obulma' ), OBULMA_VERSION ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>

		<p>
			<a href="<?php echo esc_url( $theme_uri ); ?>" class="button button-primary" target="_blank">Theme Details</a>
			<a href="https://wordpress.org/support/theme/obulma/#new-post" class="button" target="_blank">Get Support</a>
			<a href="https://wordpress.org/support/theme/obulma/reviews/#new-post" class="button" target="_blank">Leave a Review</a>
		</p>

		<div class="ns-main-content">

			<div class="ns-content-left">
				<div class="ns-grid">

					<div class="ns-card">
						<h3><span class="dashicons dashicons-admin-customizer"></span>Theme Options</h3>
						<p>Theme uses Customizer API for theme options. Using the Customizer you can easily customize different aspects of the theme.</p>
						<p><a href="<?php echo esc_url( wp_customize_url() ); ?>" class="button button-primary" target="_blank">Customize</a></p>
					</div><!-- .ns-card -->

					<div class="ns-card">
						<h3><span class="dashicons dashicons-editor-help"></span>Get Support</h3>
						<p>Got theme support question or found bug or got some feedbacks? Please visit dedicated support forum in the WordPress.org directory.</p>
						<p><a href="https://wordpress.org/support/theme/obulma/#new-post" class="button button-secondary" target="_blank">Visit Support</a></p>
					</div><!-- .ns-card -->

					<div class="ns-card">
						<h3><span class="dashicons dashicons-admin-plugins"></span>Recommended Plugins</h3>
						<ul>
							<li><a href="https://wordpress.org/plugins/woocommerce-product-tabs/" target="_blank">WooCommerce Product Tabs</a></li>
							<li><a href="https://wordpress.org/plugins/post-grid-elementor-addon/" target="_blank">Post Grid Elementor Addon</a></li>
							<li><a href="https://wordpress.org/plugins/admin-customizer/" target="_blank">Admin Customizer</a></li>
							<li><a href="https://wordpress.org/plugins/advanced-google-recaptcha/" target="_blank">Advanced Google reCAPTCHA</a></li>
							<li><a href="https://wordpress.org/plugins/nifty-coming-soon-and-under-construction-page/" target="_blank">Coming Soon & Maintenance Mode Page</a></li>
						</ul>
					</div><!-- .ns-card -->

					<div class="ns-card">
						<h3><span class="dashicons dashicons-desktop"></span>Recommended Themes</h3>
						<ul>
							<li><a href="https://wordpress.org/themes/simple-life/" target="_blank">Simple Life</a></li>
							<li><a href="https://wordpress.org/themes/obulma/" target="_blank">Obulma</a></li>
							<li><a href="https://wordpress.org/themes/blue-planet/" target="_blank">Blue Planet</a></li>
						</ul>
					</div><!-- .ns-card -->

				</div><!-- .ns-grid -->
			</div><!-- .ns-content-left -->

			<div class="ns-content-right">
				<div class="ns-box">
					<h3><span>Recent Blog Posts</span></h3>
					<div class="ns-box-content">

						<div class="ns-blog-list"></div>

					</div><!-- .ns-box-content -->
				</div><!-- .ns-box -->
			</div><!-- .ns-content-right -->

		</div><!-- .ns-main-content -->
	</div><!-- .ns-wrap -->
	<?php
}

function obulma_get_blog_feed_items() {
	$output = array();

	$rss = fetch_feed( 'https://www.nilambar.net/category/wordpress/feed' );

	$maxitems = 0;

	$rss_items = array();

	if ( ! is_wp_error( $rss ) ) {
		$maxitems  = $rss->get_item_quantity( 5 );
		$rss_items = $rss->get_items( 0, $maxitems );
	}

	if ( ! empty( $rss_items ) ) {
		foreach ( $rss_items as $item ) {
			$feed_item = array();

			$feed_item['title'] = $item->get_title();
			$feed_item['url']   = $item->get_permalink();

			$output[] = $feed_item;
		}
	}

	return $output;
}

/**
 * Load admin page assets.
 *
 * @since 1.0.0
 *
 * @param string $hook Hook name.
 */
function obulma_load_welcome_assets( $hook ) {
	if ( 'appearance_page_obulma-welcome' !== $hook ) {
		return;
	}

	wp_enqueue_style( 'obulma-welcome', get_template_directory_uri() . '/css/welcome.css', array(), OBULMA_VERSION );
	wp_enqueue_script( 'obulma-blog-posts', get_template_directory_uri() . '/js/blog-posts.js', array('jquery'), OBULMA_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'obulma_load_welcome_assets' );

/**
 * AJAX callback for blog posts.
 *
 * @since 1.0.0
 */
function obulma_get_blog_posts_ajax_callback() {
	$output = array();

	$posts = obulma_get_blog_feed_items();

	if ( ! empty( $posts ) ) {
		$output = $posts;
	}

	if ( ! empty( $output ) ) {
		wp_send_json_success( $output, 200 );
	} else {
		wp_send_json_error( $output, 404 );
	}
}
add_action( 'wp_ajax_nopriv_nsbl_get_posts', 'obulma_get_blog_posts_ajax_callback' );
add_action( 'wp_ajax_nsbl_get_posts', 'obulma_get_blog_posts_ajax_callback' );
