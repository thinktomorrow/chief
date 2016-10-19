var gulp = require('gulp');
var elixir = require('laravel-elixir');
var imagemin = require('gulp-imagemin');

/*
 * ---------------------------------------------------------------------------------
 * PATH CONFIGURATION
 * ---------------------------------------------------------------------------------
 *
 */
var assetsPath = __dirname;
var publicPath = assetsPath + '/../../../public/assets/back';
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
    mix.sass([
        '/../js/croppie/croppie.css',
        'main.scss'
    ],publicPath+'/css/main.css');
});

elixir(function(mix) {
    mix.scripts([
        'croppie/croppie.js',
        'cropper.js',
        'main.js'
    ],publicPath+'/js/main.js');
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
