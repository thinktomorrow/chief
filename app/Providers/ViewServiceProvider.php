<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'chief');

        Blade::componentNamespace('Thinktomorrow\\Chief\\App\\View\\Components', 'chief');

        // Manager components
        Blade::component('chief::manager.windows.state.windows', 'chief::window.states');
        Blade::component('chief::manager.windows.links.window', 'chief::window.links');

        // Template components
        Blade::component('chief::templates.page.layout', 'chief::page.layout');
        Blade::component('chief::templates.page.template', 'chief::page.template');
        Blade::component('chief::templates.page.header', 'chief::page.header');
        Blade::component('chief::templates.solo.layout', 'chief::solo.layout');
        Blade::component('chief::templates.solo.template', 'chief::solo.template');
        Blade::component('chief::templates.mail.layout', 'chief::mail.layout');
        Blade::component('chief::templates.mail.template', 'chief::mail.template');

        // Chief directives
        Blade::directive('adminRoute', function ($expression) {
            return "<?php echo \$manager->route({$expression}); ?>";
        });

        Blade::directive('adminCan', function ($expression) {
            return "<?php if (isset(\$manager) && \$manager->can({$expression})) { ?>";
        });

        Blade::directive('elseAdminCan', function () {
            return '<?php } else { ?>';
        });

        Blade::directive('endAdminCan', function () {
            return '<?php } ?>';
        });

        Vite::useBuildDirectory('chief/build');

        Vite::macro('img', function (string $asset) {
            try {
                return $this->asset('resources/assets/img/'.$asset);
            } catch (\Exception $e) {
                report($e);

                return '';
            }
        });

        /**
         * Works exactly like the asset() helper, but it never returns the hot asset.
         * It will always return the built asset. We need to avoid hot reload for
         * backend styles because these are set via a separate vite build.
         * Otherwise frontend styles will leak into the backend.
         */
        Vite::macro('buildAsset', function (string $asset, ?string $buildDirectory = null) {
            try {
                $buildDirectory ??= $this->buildDirectory;

                $chunk = $this->chunk($this->manifest($buildDirectory), $asset);

                return $this->assetPath($buildDirectory.'/'.$chunk['file']);
            } catch (\Exception $e) {
                report($e);

                return '';
            }
        });
    }

    public function register() {}
}
