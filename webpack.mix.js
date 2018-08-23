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
	 * Redactor wysiswyg
     * The editor will be set on all [data-editor] instances
     */
	.scripts([
		'resources/assets/js/vendors/redactor/redactor.js',
		'resources/assets/js/vendors/redactor/plugins/alignment.js',
		'resources/assets/js/vendors/redactor/plugins/imagemanager.js',
		'resources/assets/js/vendors/redactor/plugins/redactor-columns.js',
		'resources/assets/js/vendors/redactor/plugins/rich-links.js',
		'resources/assets/js/vendors/redactor/plugins/custom-classes.js',
		'resources/assets/js/vendors/redactor/plugins/video.js',
	], 'public/chief-assets/back/js/vendors/redactor.js')

	/**
	 * Slim cropper resources.
	 * Please note that copied files also get versioned! its a win :)
	 */
	.copy('resources/assets/js/vendors/slim/slim.kickstart.min.js', 'public/chief-assets/back/js/vendors')
    .copy('resources/assets/js/vendors/slim/slim.min.css', 'public/chief-assets/back/css/vendors')

	/**
	 * Copy fonts
	 */
	.copy('resources/assets/fonts', 'public/chief-assets/back/fonts');
