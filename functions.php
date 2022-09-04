<?php
/**
 * Functions and definitions
 *
 * @package Obulma
 */

if ( ! defined( 'OBULMA_VERSION' ) ) {
	define( 'OBULMA_VERSION', '1.0.7' );
}

// Load autoload.
if ( file_exists( get_parent_theme_file_path( 'vendor/autoload.php' ) ) ) {
	require_once get_parent_theme_file_path( 'vendor/autoload.php' );
	require_once get_parent_theme_file_path( 'vendor/wptt/webfont-loader/wptt-webfont-loader.php' );
}

if ( ! function_exists( 'obulma_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function obulma_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Obulma, use a find and replace
		 * to change 'obulma' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'obulma', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'obulma' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'obulma_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		add_theme_support(
			'custom-header',
			apply_filters(
				'obulma_custom_header_args',
				array(
					'default-image'      => '',
					'default-text-color' => '000000',
					'width'              => 1900,
					'height'             => 450,
					'flex-height'        => true,
					'header-text'        => false,
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Load default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );
	}

endif;

add_action( 'after_setup_theme', 'obulma_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function obulma_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'obulma_content_width', 640 );
}

add_action( 'after_setup_theme', 'obulma_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function obulma_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'obulma' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'obulma' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

add_action( 'widgets_init', 'obulma_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function obulma_scripts() {
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'obulma-font-awesome', get_template_directory_uri() . '/third-party/font-awesome/css/all' . $min . '.css', '', '5.9.0' );

	wp_enqueue_style( 'obulma-google-fonts', wptt_get_webfont_url( 'https://fonts.googleapis.com/css?family=Nunito:400,700' ), '', OBULMA_VERSION );

	wp_enqueue_style( 'obulma-style', get_stylesheet_uri(), array(), OBULMA_VERSION );

	wp_enqueue_style( 'obulma-custom', get_template_directory_uri() . '/css/custom' . $min . '.css', array( 'obulma-style' ), OBULMA_VERSION );

	wp_enqueue_script( 'obulma-navigation', get_template_directory_uri() . '/js/navigation' . $min . '.js', array(), '20151215', true );

	wp_enqueue_script( 'obulma-custom', get_template_directory_uri() . '/js/custom' . $min . '.js', array( 'jquery' ), OBULMA_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'obulma_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Nav Walker.
 */
require get_template_directory() . '/lib/navwalker/navwalker.php';

/**
 * Load helpers.
 */
require get_template_directory() . '/inc/helpers.php';

/**
 * Load welcome.
 */
require get_template_directory() . '/inc/welcome/welcome.php';
