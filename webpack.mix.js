let mix = require('laravel-mix');
let shell = require('shelljs');

require('laravel-mix-eslint');

mix.webpackConfig({
    watchOptions: { ignored: /node_modules/ },
})
    .setPublicPath(path.normalize('public/chief-assets/back'))
    .js('resources/assets/js/main.js', 'public/chief-assets/back/js')
    .js('resources/assets/js/native.js', 'public/chief-assets/back/js')
    .eslint({
        extensions: ['js'],
    })
    .sass('resources/assets/sass/main.scss', 'public/chief-assets/back/css')
    .sass('resources/assets/sass/login.scss', 'public/chief-assets/back/css')

    .version()

    .copy('resources/assets/fonts', 'public/chief-assets/back/fonts')

    .options({
        postCss: [
            require('tailwindcss')('./resources/assets/sass/tailwind.js'),
            require('autoprefixer')({
                overrideBrowserslist: ['last 40 versions'],
            }),
        ],

        // Webpack setting to ignore sass loader to follow url() paths
        processCssUrls: false,
    })

    // Imagine not having to publish chief assets manually every time webpack recompiles them.
    // Sounds like a dream right? Not anymore.
    .then(() => {
        if (mix.inProduction()) return;

        let currentDir = process.cwd();
        let symlinkedProjectDir = process.env.SYMLINKED_PROJECT_PATH;

        // If SYMLINKED_PROJECT_PATH is defined but empty, do nothing.
        if (symlinkedProjectDir === '') return;

        // If SYMLINKED_PROJECT_PATH is undefined, show info.
        if (symlinkedProjectDir === undefined) {
            shell.echo(
                'Make sure SYMLINKED_PROJECT_PATH is set in your .env file if you want to automatically publish chief assets on compilation.\n'
            );
            return;
        }

        // Change directory to symlinked project directory.
        if (shell.cd(symlinkedProjectDir).code !== 0) {
            shell.echo('Error: SYMLINKED_PROJECT_PATH needs to be a VALID path to the symlinked project directory.\n');
            shell.exit(1);
        }

        // Publish chief assets in the symlinked project.
        if (shell.exec('php artisan vendor:publish --tag=chief-assets --force').code !== 0) {
            shell.echo(`Error: couldn't publish chief assets in directory ${process.env.SYMLINKED_PROJECT_PATH}.\n`);
            shell.exit(1);
        }

        // Change directory back to current directory.
        shell.cd(currentDir);

        shell.echo(`Published chief assets in project ${process.env.SYMLINKED_PROJECT_PATH}.\n`);

        return 0;
    });
