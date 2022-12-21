<?php
/**
 * Theme Customizer
 *
 * @package Obulma
 */

/**
 * Setup theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function obulma_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'refresh';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'refresh';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'obulma_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'obulma_customize_partial_blogdescription',
			)
		);
	}
}

add_action( 'customize_register', 'obulma_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function obulma_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function obulma_customize_partial_blogdescription() {
	bloginfo( 'description' );
}
