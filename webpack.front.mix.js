const { mix } = require('laravel-mix');

mix.setPublicPath(path.normalize('public/assets/front'))
	.js('resources/assets/front/js/main.js', 'public/assets/front/js')
	.sass('resources/assets/front/sass/main.scss', 'public/assets/front/css')

	.version()

	.options({
		// Webpack setting to ignore sass loader to follow url() paths
		processCssUrls: false,
	});
