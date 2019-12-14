<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Obulma
 */

get_header();
?>

	<div id="primary" class="content-area column is-two-thirds">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'single' );

			the_post_navigation( array(
				'prev_text' => '<span class="icon"><i class="fas fa-arrow-left"></i></span>' . esc_html__( 'Previous', 'obulma' ),
				'next_text' => esc_html__( 'Next', 'obulma' ) . '<span class="icon"><i class="fas fa-arrow-right"></i></span>',
				) );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
