// Config for theme.
var themePath  = './';
var projectURL = 'http://staging.local/';

// Gulp Nodes.
var gulp        = require( 'gulp' ),
    gulpPlugins = require( 'gulp-load-plugins' )();

var fs = require('fs');

var pkg = JSON.parse(fs.readFileSync('./package.json'));

// Browser sync.
const browserSync = require('browser-sync').create();

var del = require('del');

// Error Handling.
var onError = function( err ) {
    console.log( 'An error occurred:', err.message );
    this.emit( 'end' );
};

gulp.task('scss', function () {
	const { autoprefixer, cleanCss, notify, plumber, sass, sassGlob, uglify, rename } = gulpPlugins;
   return gulp.src(themePath + 'sass/custom.scss')
       .on('error', sass.logError)
       .pipe(plumber())
       .pipe(sassGlob())
       .pipe(sass())
       .pipe(autoprefixer('last 4 version'))
       .pipe(gulp.dest('css'))
       .pipe(cleanCss())
       .pipe(rename({ extname: '.min.css' }))
       .pipe(gulp.dest('css'))
});

gulp.task('scripts', function() {
    const { plumber, rename, uglify } = gulpPlugins;
    return gulp.src( [themePath + 'scripts/*.js'] )
        .pipe(plumber())
        .pipe(gulp.dest('js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest('js'))
});

gulp.task( 'watch', function() {
    browserSync.init({
        proxy: projectURL,
        open: true
    });

    // Watch SCSS files.
    gulp.watch( themePath + 'sass/front/**/*.scss', gulp.series( 'scss' ) ).on('change',browserSync.reload);

    // Watch PHP files.
    gulp.watch( themePath + '**/**/*.php' ).on('change',browserSync.reload);

    // Watch JS files.
    gulp.watch( themePath + 'scripts/*.js', gulp.series( 'scripts' ) ).on('change',browserSync.reload);
});

gulp.task('pot', function() {
	const { run } = gulpPlugins;
	return run('wpi18n makepot --domain-path=languages --exclude=vendors,deploy').exec();
})

gulp.task('language', function() {
	const { run } = gulpPlugins;
	return run('wpi18n addtextdomain').exec();
})

gulp.task('clean:deploy', function() {
    return del('deploy')
});

gulp.task('copy:deploy', function() {
	const { zip } = gulpPlugins;
	var sourceFiles = [
		'**/*',
		'!gulpfile.js',
		'!package.json',
		'!package-lock.json',
		'!**/node_modules/**',
		'!**/deploy/**'
	];

	return gulp.src(sourceFiles)
	    .pipe(gulp.dest('deploy/' + pkg.name))
	    .pipe(zip(pkg.name + '.zip'))
	    .pipe(gulp.dest('deploy'))
});

// Tasks.
gulp.task( 'default', gulp.series('watch'));

gulp.task( 'style', gulp.series('scss'));

gulp.task( 'textdomain', gulp.series('language', 'pot'));

gulp.task( 'build', gulp.series('style', 'scripts', 'textdomain'));

gulp.task( 'deploy', gulp.series('clean:deploy', 'copy:deploy'));
