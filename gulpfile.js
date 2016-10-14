/**
 * We maintain two buildfiles: one for frontend and one for backend.
 *
 * By default the frontend build will be referenced from the root
 * For building the backend assets, you should run gulp inside
 * the ./resources/assets/back/ folder
 */

var gulp = require('gulp');
var chug = require( 'gulp-chug' );

function defaultTask()
{
	gulp.src( './resources/assets/front/gulpfile.js', { read: false } )
		.pipe( chug({
			tasks: ['watch']
		}) )
}

gulp.task('watch:front', defaultTask);
gulp.task('default', defaultTask);
gulp.task('watch', defaultTask);

gulp.task( 'watch:back', function () {
	gulp.src( './resources/assets/back/gulpfile.js', { read: false } )
		.pipe( chug({
			tasks: ['watch']
		}) )
} );