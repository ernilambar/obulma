/* jshint node:true */
module.exports = function( grunt ){
	'use strict';

	const sass = require( 'node-sass' );

	grunt.initConfig({
		pkg: grunt.file.readJSON( 'package.json' ),

		// Setting folder templates.
		dirs: {
			js: 'js',
			css: 'css',
			images: 'images'
		},

		// Other options.
		options: {
			text_domain: '<%= pkg.name %>'
		},

		// Generate POT files.
		makepot: {
			target: {
				options: {
					type: 'wp-theme',
					domainPath: 'languages',
					exclude: ['deploy/.*','node_modules/.*'],
					updateTimestamp: false,
					potHeaders: {
						'report-msgid-bugs-to': 'https://github.com/ernilambar/obulma/issues',
						'x-poedit-keywordslist': true,
						'language-team': '',
						'Language': 'en_US',
						'X-Poedit-SearchPath-0': '../../<%= pkg.name %>',
						'plural-forms': 'nplurals=2; plural=(n != 1);',
						'Last-Translator': 'Nilambar Sharma <nilambar@outlook.com>',
					}
				}
			}
		},

		// Check textdomain errors.
		checktextdomain: {
			options: {
				text_domain: '<%= options.text_domain %>',
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			files: {
				src: [
					'**/*.php',
					'!node_modules/**',
					'!deploy/**'
				],
				expand: true
			}
		},

		// Update text domain.
		addtextdomain: {
			options: {
				textdomain: '<%= options.text_domain %>',
				updateDomains: true
			},
			target: {
				files: {
					src: [
					'*.php',
					'**/*.php',
					'!node_modules/**',
					'!deploy/**',
					'!tests/**'
					]
				}
			}
		},

		// CSS minification.
		cssmin: {
			target: {
				files: [{
					expand: true,
					cwd: '<%= dirs.css %>',
					src: ['*.css', '!*.min.css'],
					dest: '<%= dirs.css %>',
					ext: '.min.css'
				}]
			}
		},

		// Uglify JS.
		uglify: {
			target: {
				options: {
					mangle: false
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>',
					src: ['*.js', '!*.min.js'],
					dest: '<%= dirs.js %>',
					ext: '.min.js'
				}]
			}
		},

		// Check JS.
		jshint: {
			options: grunt.file.readJSON('.jshintrc'),
			all: [
				'js/*.js',
				'!js/*.min.js'
			]
		},

		// Copy files to deploy.
		copy: {
			deploy: {
				src: [
					'**',
					'!.*',
					'!*.md',
					'!.*/**',
					'!tmp/**',
					'!Gruntfile.js',
					'!test.php',
					'!package.json',
					'!node_modules/**',
					'!tests/**',
					'!docs/**'
				],
				dest: 'deploy/<%= pkg.name %>',
				expand: true,
				dot: true
			}
		},

		// Clean the directory.
		clean: {
			deploy: ['deploy']
		},

		watch: {
			files: ['sass/**'],
			tasks: 'css'
		},

		sass: {
			options: {
				implementation: sass,
				"sourcemap=none": '',
				style: 'expanded'
			},
			dist: {
				files: {
					'css/custom.css': 'sass/custom.scss'
				}
			}
		}
	});

	// Load NPM tasks to be used here.
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );

	// Register tasks.
	grunt.registerTask( 'default', [] );

	grunt.registerTask( 'css', [
		'sass'
	]);
	grunt.registerTask( 'build', [
		'css',
		'cssmin',
		'uglify',
		'textdomain'
	]);

	grunt.registerTask( 'textdomain', [
		'addtextdomain',
		'makepot'
	]);

	grunt.registerTask( 'precommit', [
		'jshint'
	]);

	grunt.registerTask( 'deploy', [
		'clean:deploy',
		'copy:deploy'
	]);
};
