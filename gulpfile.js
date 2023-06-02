/**
 * Gulp Task.
 *
 * @since 1.0.0
 */

var gulp 				= require('gulp');
var gulp_rename 		= require('gulp-rename');
var gulp_concat 		= require('gulp-concat');
var gulp_include 		= require('gulp-include');

// plugin for css
var gulp_sass 			= require('gulp-sass');
var gulp_sourcemap 		= require( 'gulp-sourcemaps' );
var gulp_autoprefixer 	= require( 'gulp-autoprefixer' );

// plugin for js
var babelify 			= require( 'babelify' );
var browserify 			= require( 'browserify' );
var gulp_uglify 		= require( 'gulp-uglify' );
var vinyl_source 		= require( 'vinyl-source-stream' );
var vinyl_buffer 		= require( 'vinyl-buffer' );


/**
 * Admin CSS Task - compiling scss into minified css
 * and add sourcemap.
 *
 * @since 1.0.0
 */
var admin_css_src   = './assets/admin/src/scss/*.scss';
var admin_css_dist  = './assets/admin/dist/css/';
var admin_css_watch = 'assets/admin/src/scss/**/*.scss';
function adminCssTask( done ) {
	gulp.src( admin_css_src )
		.pipe( gulp_sourcemap.init() )
		.pipe( gulp_sass({ 
			outputStyle: 'compressed' 
		}).on( 'error', gulp_sass.logError ))
		.pipe( gulp_autoprefixer({
			cascade: false
		}))
		.pipe( gulp_rename({
			suffix: '.min'
		}))
		.pipe( gulp_sourcemap.write( './' ) )
		.pipe( gulp.dest( admin_css_dist ) );
	done();
}
gulp.task( 'admin_css_task', adminCssTask );

/**
 * Client CSS Task - compiling scss into minified css
 * and add sourcemap.
 *
 * @since 1.0.0
 */
var client_css_src   = './assets/client/src/scss/*.scss';
var client_css_dist  = './assets/client/dist/css/';
var client_css_watch = 'assets/client/src/scss/**/*.scss';
function clientCssTask( done ) {
	gulp.src( client_css_src )
		.pipe( gulp_sourcemap.init() )
		.pipe( gulp_sass({ 
			outputStyle: 'compressed' 
		}).on( 'error', gulp_sass.logError ))
		.pipe( gulp_autoprefixer({
			cascade: false
		}))
		.pipe( gulp_rename({
			suffix: '.min'
		}))
		.pipe( gulp_sourcemap.write( './' ) )
		.pipe( gulp.dest( client_css_dist ) );
	done();
}
gulp.task( 'client_css_task', clientCssTask );

/**
 * Admin JS Task - compiling javascript and convert into babel
 * and minify and add sourcemap.
 *
 * @since 1.0.0
 */
var admin_js_folder = 'assets/admin/src/js/';
var admin_js_dist   = './assets/admin/dist/js/';
var admin_js_files  = [ 'hivfw-admin.js' ];
var admin_js_watch  = 'assets/admin/src/js/*.js'; 
function adminJsTask( done ) {
	admin_js_files.map( function( file ) {
		return browserify({
			entries: [ admin_js_folder + file ]
		})
		.transform( babelify, {
			presets: ['@babel/env']
		})
		.bundle()
		.pipe( vinyl_source( file ) )
		.pipe( gulp_rename({
			suffix: '.min'
		}))
		.pipe( vinyl_buffer() )
		.pipe( gulp_sourcemap.init({
			loadMaps: true
		}))
		.pipe( gulp_uglify() )
		.pipe( gulp_sourcemap.write( './' ) )
		.pipe( gulp.dest( admin_js_dist ) );
	});
	done();
}
gulp.task( 'js_task', adminJsTask );

/**
 * Client JS Task - compiling javascript and convert into babel
 * and minify and add sourcemap.
 *
 * @since 1.0.0
 */
var client_js_folder = 'assets/client/src/js/';
var client_js_dist   = './assets/client/dist/js/';
var client_js_files  = [ 'hivfw-client.js' ];
var client_js_watch  = 'assets/client/src/js/*.js'; 
function clientJsTask( done ) {
	client_js_files.map( function( file ) {
		return browserify({
			entries: [ client_js_folder + file ]
		})
		.transform( babelify, {
			presets: ['@babel/env']
		})
		.bundle()
		.pipe( vinyl_source( file ) )
		.pipe( gulp_rename({
			suffix: '.min'
		}))
		.pipe( vinyl_buffer() )
		.pipe( gulp_sourcemap.init({
			loadMaps: true
		}))
		.pipe( gulp_uglify() )
		.pipe( gulp_sourcemap.write( './' ) )
		.pipe( gulp.dest( client_js_dist ) );
	});
	done();
}
gulp.task( 'client_js_task', clientJsTask );

/**
 * Bundle task - budle multiple files into single. Note only
 * use during deploying in the production.
 *
 * @since 1.0.0
 */
function bundleTask( done ) {
	return gulp.src('./src/manifest/bundle-css.js')
		.pipe( gulp_include() )
		.pipe( gulp_rename('bundled-front-page.css'))
		.pipe( gulp.dest('./assets/bundled/') )
	done();
}
gulp.task( 'bundle', bundleTask );

/**
 * Watch task - watch all the files defined inside, any
 * changes in the file will automatically trigger the task.
 *
 * @since 1.0.0
 */
function watchTask() {
	gulp.watch( admin_css_watch, adminCssTask );
	gulp.watch( client_css_watch, clientCssTask );
	gulp.watch( admin_js_watch, adminJsTask );
	gulp.watch( client_js_watch, clientJsTask );
}
gulp.task( 'watch', gulp.series( watchTask ) );