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
 * Main CSS Task - compiling scss into minified css
 * and add sourcemap.
 *
 * @since 1.0.0
 */
var main_css_src   = './assets/src/scss/*.scss';
var main_css_dist  = './assets/dist/css/';
var main_css_watch = 'assets/src/scss/**/*.scss';
function mainCssTask( done ) {
	gulp.src( main_css_src )
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
		.pipe( gulp.dest( main_css_dist ) );
	done();
}
gulp.task( 'css_task', mainCssTask );

/**
 * Main JS Task - compiling javascript and convert into babel
 * and minify and add sourcemap.
 *
 * @since 1.0.0
 */
var main_js_folder = 'assets/src/js/';
var main_js_dist   = './assets/dist/js/';
var main_js_files  = [ 'main.js' ];
var main_js_watch  = 'assets/src/js/*.js'; 
function mainJsTask( done ) {
	main_js_files.map( function( file ) {
		return browserify({
			entries: [ main_js_folder + file ]
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
		.pipe( gulp.dest( main_js_dist ) );
	});
	done();
}
gulp.task( 'js_task', mainJsTask );

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
	gulp.watch( main_css_watch, mainCssTask );
	gulp.watch( main_js_watch, mainJsTask );
}
gulp.task( 'watch', gulp.series( watchTask ) );