const { mix } = require('laravel-mix');

mix.js('resources/assets/back/js/main.js', 'public/assets/back/js')
   .sass('resources/assets/back/sass/main.scss', 'public/assets/back/css')

    .version()

    .options({
        // Webpack setting to ignore sass loader to follow url() paths
        processCssUrls: false,
    });
