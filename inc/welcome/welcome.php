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

	$version     = $theme_data->Version;
	$description = $theme_data->Description;
	$theme_uri   = $theme_data->ThemeURI;
	?>

	<div class="wrap about-wrap ns-wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<p class="about-text"><?php echo sprintf( esc_html__( 'Version: %s', 'obulma' ), $version ); ?></p>

		<div class="about-text">
			<?php echo wp_kses_post( wpautop( $description ) ); ?>
		</div>

		<p>
			<a href="<?php echo esc_url( $theme_uri ); ?>" class="button button-primary" target="_blank">Theme Details</a>
			<a href="https://wordpress.org/support/theme/obulma/#new-post" class="button" target="_blank">Get Support</a>
			<a href="https://wordpress.org/support/theme/obulma/reviews/#new-post" class="button" target="_blank">Leave a Review</a>
		</p>

		<div class="ns-page">
			<div class="ns-content">
				<div class="ns-section cols-2">
					<div class="card">
						<h3>Theme Options</h3>
						<p>Theme uses Customizer API for theme options. Using the Customizer you can easily customize different aspects of the theme.</p>
						<a href="<?php echo esc_url( wp_customize_url() ); ?>" class="button button-primary" target="_blank">Customize</a>
					</div><!-- .card -->
					<div class="card">
						<h3>Get Support</h3>
						<p>Got theme support question or found bug or got some feedbacks? Please visit dedicated support forum in the WordPress.org directory.</p>
						<a href="https://wordpress.org/support/theme/obulma/#new-post" class="button button-secondary" target="_blank">Visit Support</a>
					</div><!-- .card -->
					<div class="card">
						<h3>Recommended Plugins</h3>
						<ul>
							<li><a href="https://wordpress.org/plugins/woocommerce-product-tabs/" target="_blank">WooCommerce Product Tabs</a></li>
							<li><a href="https://wordpress.org/plugins/post-grid-elementor-addon/" target="_blank">Post Grid Elementor Addon</a></li>
							<li><a href="https://wordpress.org/plugins/admin-customizer/" target="_blank">Admin Customizer</a></li>
							<li><a href="https://wordpress.org/plugins/advanced-google-recaptcha/" target="_blank">Advanced Google reCAPTCHA</a></li>
							<li><a href="https://wordpress.org/plugins/nifty-coming-soon-and-under-construction-page/" target="_blank">Coming Soon & Maintenance Mode Page</a></li>
						</ul>
					</div><!-- .card -->
					<div class="card">
						<h3>Recommended Themes</h3>
						<ol>
							<li><a href="https://wordpress.org/themes/simple-life/" target="_blank">Simple Life</a></li>
							<li><a href="https://wordpress.org/themes/obulma/" target="_blank">Obulma</a></li>
							<li><a href="https://wordpress.org/themes/blue-planet/" target="_blank">Blue Planet</a></li>
						</ol>
					</div><!-- .card -->

				</div><!-- .ns-section -->
			</div><!-- .ns-content -->
			<div class="ns-sidebar">
				<div class="ns-box">
					<h3><span>Recent Blog Posts</span></h3>
					<div class="ns-box-content">
						<?php $rss_items = obulma_get_blog_feed_items(); ?>

						<?php if ( ! empty( $rss_items ) ) : ?>
							<ul>
								<?php foreach ( $rss_items as $item ) : ?>
									<li><a href="<?php echo esc_url( $item['url'] ); ?>" target="_blank"><?php echo esc_html( $item['title'] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div> <!-- .ns-box-content -->

				</div><!-- .postbox -->
			</div><!-- .ns-sidebar -->
		</div><!-- .ns-page -->

	</div>
	<?php
}

/**
 * Load welcome page styles.
 *
 * @since 1.0.0
 */
function obulma_add_welcome_style() {
	$current_screen = get_current_screen();

	if ( ! $current_screen ) {
		return;
	}

	if ( 'appearance_page_obulma-welcome' !== $current_screen->id ) {
		return;
	}
	?>
	<style>
		.ns-wrap .ns-page {
			display: flex;
			gap: 1rem;
		}
		.ns-wrap .ns-content {
			width: 100%;
		}
		.ns-wrap .ns-section {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 1rem;
		}
		.ns-wrap .ns-sidebar {
			flex: 0 0 280px;
		}
		.ns-wrap .card {
			margin-top: 0;
			padding: 0.7em 1em 1em;
		}
		.ns-wrap h3 {
			font-size: 1.3em;
			margin: 0 0 0.6em;
		}
		.ns-wrap h3 span {
			font-size: 1em;
		}
		.ns-wrap p,
		.about-wrap .about-text {
			font-size: 15px;
		}
		.ns-wrap .ns-page ol,
		.ns-wrap .ns-page ul {
			margin-left: 1em;
			list-style-position: outside;
		}
		.ns-wrap .ns-page ul {
			list-style-type: disc;
		}
	</style>
	<?php
}

add_action( 'admin_head', 'obulma_add_welcome_style' );

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
