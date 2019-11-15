let mix = require('laravel-mix');
require('laravel-mix-purgecss');

mix.webpackConfig({
	watchOptions: {
		ignored: /node_modules/
	}
})
	.setPublicPath(path.normalize('public/chief-assets/back'))
	.js('resources/assets/js/main.js', 'public/chief-assets/back/js')
	.js('resources/assets/js/native.js', 'public/chief-assets/back/js')
	.sass('resources/assets/sass/main.scss', 'public/chief-assets/back/css')
	.sass('resources/assets/sass/login.scss', 'public/chief-assets/back/css')

	.version()

	.options({
		postCss: [
			require('tailwindcss')('./resources/assets/sass/tailwind.js'),
			require('autoprefixer')({
                overrideBrowserslist: ['last 40 versions'],
			}),
		],

		autoprefixer: true,

        // Webpack setting to ignore sass loader to follow url() paths
		processCssUrls: false,
	
	})

    .purgeCss({
        folders: [
            'resources/assets/',
            'resources/views/',
            'app',
            'src'
		],
		whitelistPatterns: [/slim-/]
    })

	
	/**
	 * Copy fonts
	 */
	.copy('resources/assets/fonts', 'public/chief-assets/back/fonts');
