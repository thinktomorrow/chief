let mix = require('laravel-mix');

mix.setPublicPath(path.normalize('public/assets/back'))
	.js('resources/assets/back/js/main.js', 'public/assets/back/js')
	.js('resources/assets/back/js/catalog.js', 'public/assets/back/js')
	.sass('resources/assets/back/sass/main.scss', 'public/assets/back/css')

	.version()

	.options({
		// Webpack setting to ignore sass loader to follow url() paths
		processCssUrls: false
	});

/**
 * Slim cropper resources.
 * Please note that copied files also get versioned! its a win :)
 */
mix.copy('resources/assets/back/js/vendors/slim/slim.kickstart.min.js', 'public/assets/back/js');
mix.copy('resources/assets/back/js/vendors/slim/slim.min.css', 'public/assets/back/css');
