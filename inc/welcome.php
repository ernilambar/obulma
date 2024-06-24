<?php
/**
 * Welcome
 *
 * @package Obulma
 */

use Nilambar\Welcome\Welcome;

add_action(
	'wp_welcome_init',
	function () {
		$obj = new Welcome( 'theme', 'obulma' );

		$obj->set_page(
			array(
				'menu_title'  => esc_html__( 'Obulma', 'obulma' ),
				'menu_slug'   => 'obulma-welcome',
				'parent_page' => 'themes.php',
			)
		);

		$obj->set_admin_notice(
			array(
				'screens' => array( 'themes', 'dashboard' ),
			)
		);

		$obj->set_quick_links(
			array(
				array(
					'text' => 'Theme Details',
					'url'  => 'https://www.nilambar.net/2019/12/obulma-free-wordpress-theme.html',
					'type' => 'primary',
				),
				array(
					'text' => 'Get Support',
					'url'  => 'https://wordpress.org/support/theme/obulma/#new-post',
					'type' => 'secondary',
				),
				array(
					'text' => 'Leave a Review',
					'url'  => 'https://wordpress.org/support/theme/obulma/reviews/#new-post',
					'type' => 'secondary',
				),
			)
		);

		$obj->add_tab(
			array(
				'id'    => 'getting-started',
				'title' => esc_html__( 'Getting Started', 'obulma' ),
				'type'  => 'grid',
				'items' => array(
					array(
						'title'       => 'Theme Options',
						'icon'        => 'dashicons dashicons-admin-customizer',
						'description' => 'Theme uses Customizer API for theme options. Using the Customizer you can easily customize different aspects of the theme.',
						'button_text' => 'Go to Customizer',
						'button_url'  => wp_customize_url(),
						'button_type' => 'primary',
					),
					array(
						'title'       => 'Get Support',
						'icon'        => 'dashicons dashicons-editor-help',
						'description' => 'Got theme support question or found bug or got some feedbacks? Please visit support forum in the WordPress.org directory.',
						'button_text' => 'Visit Support',
						'button_url'  => 'https://wordpress.org/support/theme/obulma/#new-post',
						'button_type' => 'secondary',
						'is_new_tab'  => true,
					),
					array(
						'title'       => 'Recommended Plugins',
						'icon'        => 'dashicons dashicons-admin-plugins',
						'description' => '<ul>
													<li><a href="https://wordpress.org/plugins/ns-featured-posts/" target="_blank">NS Featured Posts</a></li>
													<li><a href="https://wordpress.org/plugins/ns-category-widget/" target="_blank">NS Category Widget</a></li>
													<li><a href="https://wordpress.org/plugins/admin-customizer/" target="_blank">Admin Customizer</a></li>
													<li><a href="https://wordpress.org/plugins/date-today-nepali/" target="_blank">Date Today Nepali</a></li>
												</ul>',
					),
					array(
						'title'       => 'Recommended Themes',
						'icon'        => 'dashicons dashicons-desktop',
						'description' => '<ul>
														<li><a href="https://wordpress.org/themes/niya/" target="_blank">Niya</a></li>
														<li><a href="https://wordpress.org/themes/simple-life/" target="_blank">Simple Life</a></li>
														<li><a href="https://wordpress.org/themes/obulma/" target="_blank">Obulma</a></li>
														<li><a href="https://wordpress.org/themes/blue-planet/" target="_blank">Blue Planet</a></li>
													</ul>',
					),
				),
			)
		);

		$obj->set_sidebar(
			array(
				'render_callback' => 'obulma_render_welcome_page_sidebar',
			)
		);

		$obj->run();
	}
);

/**
 * Return blog posts list.
 *
 * @since 1.0.0
 *
 * @return array Posts list.
 */
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

add_action( 'wp_ajax_nopriv_obulma_nsbl_get_posts', 'obulma_get_blog_posts_ajax_callback' );
add_action( 'wp_ajax_obulma_nsbl_get_posts', 'obulma_get_blog_posts_ajax_callback' );

/**
 * Render welcome page sidebar.
 *
 * @since 1.0.0
 *
 * @param Welcome $welcome_object Instance of Welcome class.
 */
function obulma_render_welcome_page_sidebar( $welcome_object ) {
	$welcome_object->render_sidebar_box(
		array(
			'title'        => 'Leave a Review',
			'content'      => $welcome_object->get_stars() . sprintf( 'Are you enjoying %s? We would appreciate a review.', $welcome_object->get_name() ),
			'button_text'  => 'Submit Review',
			'button_url'   => 'https://wordpress.org/support/theme/obulma/reviews/#new-post',
			'button_class' => 'button',
		),
		$welcome_object
	);

	$welcome_object->render_sidebar_box(
		array(
			'title'   => 'Recent Blog Posts',
			'content' => '<div class="ns-blog-list"></div>',
		),
		$welcome_object
	);
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

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'obulma-welcome', get_template_directory_uri() . '/build/welcome' . $min . '.js', array( 'jquery' ), OBULMA_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'obulma_load_welcome_assets' );
