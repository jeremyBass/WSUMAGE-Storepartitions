
module.exports = function(grunt) {
	//lets look for configs
	function loadConfig(path) {
	  var glob = require('glob');

	  var object = {};
	  var key;

	  glob.sync('*', {cwd: path}).forEach(function(option) {
		key = option.replace(/\.js$/,'');
		object[key] = require(path + option);
	  });

	  return object;
	}

	var pkg,setbase,config;

	pkg = grunt.file.readJSON('package.json');
	setbase = grunt.option('setbase') || pkg.build_location+'/'+pkg.build_version+'/';

	config = {
		pkg: grunt.file.readJSON('package.json'),
		setbase:setbase,
		config: {
			build: 'build'
		},
		env : {
			options : {
				/* Shared Options Hash */
				//globalOption : 'foo'
			},
			dev: {
				NODE_ENV : 'DEVELOPMENT'
			},
			prod : {
				NODE_ENV : 'PRODUCTION'
			}
		},
		copy: {
			main: {
				files: [
					{expand: true,flatten: true, src: ['builder/pub/build/*.html'], dest: 'site/'},
					{expand: true,flatten: true, src: ['builder/pub/build/index.html'], dest: ''}
				]
			},
			defaults: {
				files: [
					{expand: true,flatten:false, src: ['builder/templates/assests'], dest: 'site/assests/'},
					{expand: true,flatten: true, src: ['builder/pub/build/index.html'], dest: ''}
				]
			}
		},
		includereplace: {
			prep: {
				// Files to perform replacements and includes with
				src: 'builder/build/*.html',
				// Destination directory to copy files to
				dest: 'builder/pub/'
			},
		},
		preprocess : {
			options: {
				inline: true,
				context : {
					DEBUG: true,
					build_version : '<%= pkg.build_version %>',
				}
			},
			index : {
				src : 'src/index.html',
				dest : 'builder/build/index.html',
				options : {
					context : {
						page : 'index'
					}
				}
			},
		}
	};
 
	grunt.util._.extend(config, loadConfig('./grunt/tasks/options/'));
	grunt.initConfig(config);

	require('load-grunt-tasks')(grunt);
	grunt.loadTasks('builder/tasks');


	// Default task(s).
	grunt.registerTask('default', ['jshint']);
	grunt.registerTask('prod', ['env:prod', 'concat','preprocess:js','cssmin','uglify','copy','includereplace','preprocess:html']);	
	
	grunt.registerTask('dev', [
								'env:dev',
								'preprocess',
								'includereplace',
								'copy',
								]);
		
		
};