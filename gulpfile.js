// Dotenv.
require( 'dotenv' ).config();

// Config.
var rootPath = './';

// Gulp.
var gulp = require( 'gulp' );

// Browser sync.
var browserSync = require( 'browser-sync' ).create();

// SASS.
var sass = require( 'gulp-sass' )( require( 'sass' ) );

// Plumber.
var plumber = require( 'gulp-plumber' );

// Rename.
var rename = require( 'gulp-rename' );

// Uglify.
var uglify = require( 'gulp-uglify' );

// Autoprefixer.
var autoprefixer = require( 'gulp-autoprefixer' );

// Clean CSS.
var cleanCSS = require( 'gulp-clean-css' );

gulp.task( 'style', function() {
	return gulp.src( rootPath + 'sass/custom.scss' )
		.on( 'error', sass.logError )
		.pipe( plumber() )
		.pipe( sass() )
		.pipe( autoprefixer() )
		.pipe( gulp.dest( 'css' ) )
		.pipe( cleanCSS() )
		.pipe( rename( { extname: '.min.css' } ) )
		.pipe( gulp.dest( 'css' ) );
} );

gulp.task( 'scripts', function() {
	return gulp.src( [ rootPath + 'scripts/*.js' ] )
		.pipe( gulp.dest( 'js' ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( uglify() )
		.pipe( gulp.dest( 'js' ) );
} );

gulp.task( 'watch', function() {
	browserSync.init( {
		proxy: process.env.DEV_SERVER_URL,
		open: true,
	} );

	// Watch SCSS files.
	gulp.watch( rootPath + 'sass/**/*.scss', gulp.series( 'style' ) ).on( 'change', browserSync.reload );

	// Watch PHP files.
	gulp.watch( rootPath + '**/**/*.php' ).on( 'change', browserSync.reload );

	// Watch JS files.
	gulp.watch( rootPath + 'scripts/*.js', gulp.series( 'scripts' ) ).on( 'change', browserSync.reload );
} );

// Tasks.
gulp.task( 'default', gulp.series( 'watch' ) );
gulp.task( 'build', gulp.series( 'style', 'scripts' ) );
