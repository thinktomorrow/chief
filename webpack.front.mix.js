const { mix } = require('laravel-mix');

mix.js('resources/assets/front/js/main.js', 'public/assets/js')
   .sass('resources/assets/front/sass/main.scss', 'public/assets/css')

    .version()

    .options({
        // Webpack setting to ignore sass loader to follow url() paths
        processCssUrls: false,
    });
