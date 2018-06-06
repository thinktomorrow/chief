let mix = require('laravel-mix');

mix.setPublicPath(path.normalize('public/chief-assets/back'))
	.js('resources/assets/js/main.js', 'public/chief-assets/back/js')
	.js('resources/assets/js/native.js', 'public/chief-assets/back/js')
	.sass('resources/assets/sass/main.scss', 'public/chief-assets/back/css')
	.sass('resources/assets/sass/login.scss', 'public/chief-assets/back/css')

	.version()

	.options({
		// Webpack setting to ignore sass loader to follow url() paths
		processCssUrls: false
	})

/**
 * Slim cropper resources.
 * Please note that copied files also get versioned! its a win :)
 */
	.copy('resources/assets/js/vendors/slim/slim.kickstart.min.js', 'public/chief-assets/back/js/vendors')
    .copy('resources/assets/js/vendors/slim/slim.min.css', 'public/chief-assets/back/css/vendors');
