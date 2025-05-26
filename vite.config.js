import { defineConfig } from 'vite';
import eslint from 'vite-plugin-eslint';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import shell from 'shelljs';
import dotenv from 'dotenv';

export default defineConfig({
    build: {
        outDir: 'public/chief/build',
        assetsDir: 'assets',
    },
    plugins: [
        eslint({
            include: ['resources/assets/**/*.js'],
            failOnWarning: false,
            failOnError: false,
        }),
        laravel({
            input: ['resources/assets/css/main.css', 'resources/assets/js/main.js'],
            refresh: true,
        }),
        tailwindcss(),
        {
            closeBundle() {
                const currentDir = process.cwd();
                const symlinkedProjectDir = dotenv.config().parsed.SYMLINKED_PROJECT_PATH;

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
                    shell.echo(
                        'Error: SYMLINKED_PROJECT_PATH needs to be a VALID path to the symlinked project directory.\n'
                    );
                    shell.exit(1);
                }

                // Publish chief assets in the symlinked project.
                if (shell.exec('php artisan vendor:publish --tag=chief-assets --force').code !== 0) {
                    shell.echo(`Error: couldn't publish chief assets in directory ${symlinkedProjectDir}.\n`);
                    shell.exit(1);
                }

                // Change directory back to current directory.
                shell.cd(currentDir);

                shell.echo(`Published chief assets in project ${symlinkedProjectDir}.\n`);

                return 0;
            },
        },
    ],
});
