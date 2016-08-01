<?php

namespace Chief\Trans;

use Chief\Trans\Handlers\ClearTranslationsOnDisk;
use Chief\Trans\Handlers\ReadOriginalTranslationsFromDisk;
use Chief\Trans\Handlers\WriteTranslationLineToDisk;
use Illuminate\Translation\TranslationServiceProvider as BaseServiceProvider;
use League\Flysystem\Adapter\Local;

class TransServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $transpath = storage_path('app/trans');

        $this->app['translator']->addNamespace('tt',$transpath);

    }

    /**
     * Register our translator
     *
     * @return void
     */
    public function register()
    {
        $this->registerTranslator();

        $path = storage_path('app/trans');

        $this->app->bind(ClearTranslationsOnDisk::class,function($app) use($path){
            return new ClearTranslationsOnDisk(
                new \League\Flysystem\Filesystem(new Local($path))
            );
        });

        $this->app->bind(WriteTranslationLineToDisk::class,function($app) use($path){
            return new WriteTranslationLineToDisk(
                new \League\Flysystem\Filesystem(new Local($path))
            );
        });

        $this->app->bind(ReadOriginalTranslationsFromDisk::class,function($app){
            return new ReadOriginalTranslationsFromDisk(
                new \League\Flysystem\Filesystem(new Local(base_path('resources/lang')))
            );
        });
    }

    private function registerTranslator()
    {
        $this->registerLoader();

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
