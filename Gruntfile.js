module.exports = function(grunt) {
	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON( 'package.json' ),

		replace : {
			readme: {
				options: {
					patterns: [
						{
							match: /Stable tag:\s?(.+)/gm,
							replacement: 'Stable tag: <%= pkg.version %>'
						}
					]
				},
				files: [
					{
						expand: true, flatten: true, src: ['readme.txt'], dest: './'
					}
				]
			},
			style: {
				options: {
					patterns: [
						{
							match: /Version:\s?(.+)/gm,
							replacement: 'Version: <%= pkg.version %>'
						}
					]
				},
				files: [
					{
						expand: true, flatten: true, src: ['style.css'], dest: './'
					}
				]
			},
			functions: {
				options: {
					patterns: [
						{
							match: /define\( \'OBULMA_VERSION\'\, \'(.+)\'/gm,
							replacement: "define( 'OBULMA_VERSION', '<%= pkg.version %>'"
						}
					]
				},
				files: [
					{
						expand: true, flatten: true, src: ['functions.php'], dest: './'
					}
				]
			}
		}
	});

	grunt.loadNpmTasks('grunt-replace');

	grunt.registerTask('version', ['replace:readme', 'replace:style', 'replace:functions']);
};
