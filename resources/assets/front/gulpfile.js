var gulp = require('gulp');
var elixir = require('laravel-elixir');
var imagemin = require('gulp-imagemin');

/*
 * ---------------------------------------------------------------------------------
 * LOCALHOST
 * ---------------------------------------------------------------------------------
 *
 * Set this proxy to match your localhost.
 * e.g. localhost:8888 or project.dev
 */
var localhost = 'localhost:8888';

/*
 * ---------------------------------------------------------------------------------
 * PATHS
 * ---------------------------------------------------------------------------------
 */
var assetsPath = __dirname;
var publicPath = assetsPath + '/../../../public/assets';

elixir.config.assetsPath = assetsPath;
elixir.config.publicPath = publicPath;

/*
 * ---------------------------------------------------------------------------------
 * ASSET MANAGEMENT
 * ---------------------------------------------------------------------------------
 *
 * add --production flag to minify the generated assets
 */

elixir(function(mix) {
    mix.sass('main.scss',publicPath+'/css/main.css').browserSync({
        proxy: localhost
    });
});

elixir(function(mix) {
    mix.scriptsIn(assetsPath+'/js',publicPath+'/js/combined.js');
});

/*
 * ---------------------------------------------------------------------------------
 * COMPRESS YOUR IMAGES
 * ---------------------------------------------------------------------------------
 *
 * add --verbose as flag to your command to see compression of each image
 */
gulp.task('optimize:images',function(){
    gulp.src('../www/assets/img/**/*.{jpg,jpeg,png,gif}')
        .pipe(imagemin({
            progressive: true,
            interlaced: true,
            optimizationLevel: 4
        }))
        .pipe(gulp.dest('./resources/.cache/img/'))
        .pipe(gulp.dest('../www/assets/img/'));
});