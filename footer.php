<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Obulma
 */

?>
			</div><!-- .columns -->
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer footer">
		<div class="container">
			<div class="columns">
				<div class="column">
					<?php esc_html_e( 'Copyright &copy; All rights reserved.', 'obulma' ); ?>
				</div><!-- .column -->
				<div class="column">
					<div class="site-info is-pulled-right">
						<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'obulma' ) ); ?>">
							<?php
							/* translators: %s: CMS name, i.e. WordPress. */
							printf( esc_html__( 'Proudly powered by %s', 'obulma' ), 'WordPress' );
							?>
						</a>
						<span class="sep"> | </span>
							<?php
							/* translators: 1: Theme name, 2: Theme author. */
							printf( esc_html__( 'Theme: %1$s by %2$s.', 'obulma' ), 'Obulma', '<a href="https://www.nilambar.net/">Nilambar Sharma</a>' );
							?>
					</div><!-- .site-info -->
				</div><!-- .column -->
			</div><!-- .columns -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
