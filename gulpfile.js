// Env.
require( 'dotenv' ).config();

// Config.
const rootPath = './';

// Gulp.
const gulp = require( 'gulp' );

// Browser sync.
const browserSync = require( 'browser-sync' ).create();

// SASS.
const sass = require( 'gulp-sass' )( require( 'sass' ) );

// Plumber.
const plumber = require( 'gulp-plumber' );

// Rename.
const rename = require( 'gulp-rename' );

// Uglify.
const uglify = require( 'gulp-uglify' );

// Autoprefixer.
const autoprefixer = require( 'gulp-autoprefixer' );

// Clean CSS.
const cleanCSS = require( 'gulp-clean-css' );

// SASS Glob.
const sassGlob = require( 'gulp-sass-glob' );

gulp.task( 'scss', function() {
	return gulp.src( rootPath + 'sass/custom.scss' )
		.on( 'error', sass.logError )
		.pipe( plumber() )
		.pipe( sassGlob() )
		.pipe( sass() )
		.pipe( autoprefixer( 'last 4 version' ) )
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
	gulp.watch( rootPath + 'sass/**/*.scss', gulp.series( 'scss' ) ).on( 'change', browserSync.reload );

	// Watch PHP files.
	gulp.watch( rootPath + '**/**/*.php' ).on( 'change', browserSync.reload );

	// Watch JS files.
	gulp.watch( rootPath + 'scripts/*.js', gulp.series( 'scripts' ) ).on( 'change', browserSync.reload );
} );

// Tasks.
gulp.task( 'default', gulp.series( 'watch' ) );

gulp.task( 'style', gulp.series( 'scss' ) );

gulp.task( 'build', gulp.series( 'style', 'scripts' ) );
