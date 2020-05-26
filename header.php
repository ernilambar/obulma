<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Obulma
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'obulma' ); ?></a>

	<nav class="navbar" role="navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'obulma' ); ?>">
		<div class="container">
			<div class="navbar-brand">
				<?php the_custom_logo(); ?>

				<a role="button" class="navbar-burger burger" aria-expanded="false" data-target="main-menu">
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
				</a>
			</div>

			<div id="main-menu" class="navbar-menu">
				<div class="navbar-end">
					<?php
					wp_nav_menu( array(
						'theme_location'  => 'menu-1',
						'container'       => '',
						'container_class' => '',
						'container_id'    => '',
						'menu_class'      => '',
						'menu_id'         => '',
						'fallback_cb'     => 'obulma_primary_navigation_fallback',
						'items_wrap'      => '%3$s',
						'depth'           => 2,
						'walker'          => new BulmaWP_Navbar_Walker,
						) );
						?>
				</div>
			</div>
		</div><!-- .container -->
	</nav>

	<header id="masthead" class="site-header header-content">
		<?php
		$banner_image = get_header_image();
		$extra_style = '';
		if ( $banner_image ) {
			$extra_style .= 'background-image: url(' . esc_url( $banner_image ) . ');background-size:cover;';
		}
		?>
		<section class="hero is-light" style=" <?php echo esc_attr( $extra_style ); ?>">
			<div class="hero-body">
				<div class="container has-text-centered">
					<?php
					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="title site-title is-1 is-spaced"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					else :
						?>
						<p class="title site-title is-1 is-spaced"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					endif;
					?>
					<?php
					$obulma_description = get_bloginfo( 'description', 'display' );
					if ( $obulma_description || is_customize_preview() ) :
						?>
						<p class="site-description subtitle"><?php echo $obulma_description; /* WPCS: xss ok. */ ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</header>

	<div id="content" class="site-content">
		<div class="container">
			<div class="columns">

