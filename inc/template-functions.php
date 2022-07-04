<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Obulma
 */

/**
 * Custom body classes.
 *
 * @since 1.0.0
 *
 * @param array $classes Classes for the body element.
 * @return array Modified classes.
 */
function obulma_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'obulma_body_classes' );

/**
 * Add pingback url.
 *
 * @since 1.0.0
 */
function obulma_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

add_action( 'wp_head', 'obulma_pingback_header' );

/**
 * Next posts link class.
 *
 * @since 1.0.0
 */
function obulma_next_posts_link_class() {
	return 'class="pagination-next"';
}

add_filter( 'next_posts_link_attributes', 'obulma_next_posts_link_class' );

/**
 * Previous posts link class.
 *
 * @since 1.0.0
 */
function obulma_previous_posts_link_class() {
	return 'class="pagination-previous"';
}

add_filter( 'previous_posts_link_attributes', 'obulma_previous_posts_link_class' );

/**
 * Customize tag cloud widget arguments.
 *
 * @since 1.0.0
 *
 * @param array $args Arguments.
 * @return array Modified arguments.
 */
function obulma_widget_tag_cloud_args( $args ) {
	$args['largest']  = 16;
	$args['smallest'] = 16;
	$args['unit']     = 'px';
	$args['format']   = 'flat';

	return $args;
}

add_filter( 'widget_tag_cloud_args', 'obulma_widget_tag_cloud_args' );

/**
 * Customize comment form fields.
 *
 * @since 1.0.0
 *
 * @param array $fields Field details.
 * @return array Modified fields.
 */
function obulma_customize_comment_form_fields( $fields ) {
	$commenter     = wp_get_current_commenter();
	$user          = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$req      = get_option( 'require_name_email' );
	$html_req = ( $req ? " required='required'" : '' );

	$fields['author'] = '<div class="field"><label for="author" class="label">' . esc_html__( 'Name', 'obulma' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> <div class="control has-icons-left"><input id="author" name="author" type="text" class="input" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $html_req . ' /><span class="icon is-small is-left"><i class="fas fa-user"></i></span></div></div>';
	$fields['email']  = '<div class="field"><label for="email" class="label">' . esc_html__( 'Email', 'obulma' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> <div class="control has-icons-left"><input id="email" name="email" type="email" class="input" value="' . esc_attr( $commenter['comment_author_email'] ) . '" ' . $html_req . ' /><span class="icon is-small is-left"><i class="fas fa-envelope"></i></span></div></div>';
	$fields['url']    = '<div class="field"><label for="url" class="label">' . esc_html__( 'Website', 'obulma' ) . '</label> <div class="control has-icons-left"><input id="url" name="url" type="url" class="input" value="' . esc_attr( $commenter['comment_author_url'] ) . '" /><span class="icon is-small is-left"><i class="fas fa-globe-americas"></i></span></div></div>';

	if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) && get_option( 'show_comments_cookies_opt_in' ) ) {
		$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

		$fields['cookies'] = '<div class="field"><div class="control"><label for="wp-comment-cookies-consent" class="checkbox"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' /> ' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'obulma' ) . '</label></div></div>';
	}

	return $fields;
}

add_filter( 'comment_form_default_fields', 'obulma_customize_comment_form_fields' );

/**
 * Customize comment form defaults.
 *
 * @since 1.0.0
 *
 * @param array $defaults Form details.
 * @return array Modified details.
 */
function obulma_customize_comment_form( $defaults ) {
	$defaults['class_submit']  = 'button is-info';
	$defaults['comment_field'] = '<div class="field"><label for="comment" class="label">' . esc_html__( 'Comment', 'obulma' ) . '</label><div class="control"><textarea class="textarea" id="comment" name="comment" maxlength="65525" required="required"></textarea></div></div>';

	return $defaults;
}

add_filter( 'comment_form_defaults', 'obulma_customize_comment_form' );

/**
 * Add admin notice.
 *
 * @since 1.0.6
 */
function obulma_add_admin_notice() {
	// Setup notice.
	\Nilambar\AdminNotice\Notice::init(
		array(
			'slug' => 'obulma',
			'type' => 'theme',
			'name' => esc_html__( 'Obulma', 'obulma' ),
		)
	);
}

add_action( 'admin_init', 'obulma_add_admin_notice' );
