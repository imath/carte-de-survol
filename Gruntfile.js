/* jshint node:true */
/* global module */
module.exports = function( grunt ) {
	require( 'matchdep' ).filterDev( ['grunt-*', '!grunt-legacy-util'] ).forEach( grunt.loadNpmTasks );
	grunt.util = require( 'grunt-legacy-util' );

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),
		jshint: {
			options: grunt.file.readJSON( '.jshintrc' ),
			grunt: {
				src: ['Gruntfile.js']
			},
			all: ['Gruntfile.js', 'assets/js/*.js']
		},
		checktextdomain: {
			options: {
				correct_domain: false,
				text_domain: ['carte-de-survol'],
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'_n:1,2,4d',
					'_ex:1,2c,3d',
					'_nx:1,2,4c,5d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			files: {
				src: ['**/*.php', '!**/node_modules/**'],
				expand: true
			}
		},
		clean: {
			all: ['assets/css/*.min.css', 'assets/js/*.min.js']
		},
		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					exclude: ['/node_modules'],
					mainFile: 'carte-de-survol.php',
					potFilename: 'carte-de-survol.pot',
					processPot: function( pot ) {
						pot.headers['last-translator']      = 'imath <contact@imathi.eu>';
						pot.headers['language-team']        = 'FRENCH <contact@imathi.eu>';
						pot.headers['report-msgid-bugs-to'] = 'https://github.com/imath/carte-de-survol/issues';
						return pot;
					},
					type: 'wp-plugin'
				}
			}
		},
		uglify: {
			minify: {
				extDot: 'last',
				expand: true,
				ext: '.min.js',
				src: ['assets/js/*.js', '!*.min.js']
			}
		},
		cssmin: {
			minify: {
				extDot: 'last',
				expand: true,
				ext: '.min.css',
				src: ['assets/css/*.css', '!*.min.css']
			}
		},
		jsvalidate:{
			src: ['assets/js/*.js'],
			options:{
				globals: {},
				esprimaOptions:{},
				verbose: false
			}
		},
		'git-archive': {
			archive: {
				options: {
					'format'  : 'zip',
					'output'  : '<%= pkg.name %>.zip',
					'tree-ish': 'HEAD@{0}'
				}
			}
        },
        exec: {
            wpcs: {
                command: './vendor/bin/phpcs *.php inc/*.php --standard=WordPress',
                stdout: true,
				stderr: true
            }
        }
	} );

	grunt.registerTask( 'jstest', ['jsvalidate', 'jshint'] );

    grunt.registerTask( 'phpcs', 'exec:wpcs' );

	grunt.registerTask( 'shrink', ['cssmin', 'uglify'] );

	grunt.registerTask( 'compress', ['git-archive'] );

	grunt.registerTask( 'release', ['phpcs', 'checktextdomain', 'makepot', 'clean', 'jstest', 'shrink'] );

	// Default task.
	grunt.registerTask( 'default', ['phpcs'] );
};
