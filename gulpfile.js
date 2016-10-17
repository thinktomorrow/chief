/**
 * We maintain two buildfiles: one for frontend and one for backend.
 *
 * By default the frontend build will be referenced from the root
 * For building the backend assets, you should run gulp inside
 * the ./resources/assets/back/ folder
 */

var gulp = require('gulp');
var chug = require( 'gulp-chug' );

var pathFront = './resources/assets/front/gulpfile.js';
var pathBack = './resources/assets/back/gulpfile.js';

// Piping the commands
gulp.task('watch:front', watchTaskFront);
gulp.task('watch:back', watchTaskBack);
gulp.task('default:front', defaultTaskFront);
gulp.task('default:back', defaultTaskBack);

// Frontend shortcuts
gulp.task('default', defaultTaskFront);
gulp.task('watch', watchTaskFront);

function defaultTaskFront(){ return pipeCommand('default','front');}
function watchTaskFront(){ return pipeCommand('watch','front');}
function defaultTaskBack(){ return pipeCommand('default','back');}
function watchTaskBack(){ return pipeCommand('watch','back');}

function pipeCommand(command,env)
{
	var paths = {
		front: pathFront,
		back: pathBack,
	};

	gulp.src( paths[env], { read: false } )
		.pipe( chug({
			tasks: [command]
		}) )
}
