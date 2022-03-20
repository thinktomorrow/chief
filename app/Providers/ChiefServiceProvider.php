<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\Sitemap\SitemapServiceProvider;
use Thinktomorrow\AssetLibrary\AssetLibraryServiceProvider;
use Thinktomorrow\Chief\Admin\Authorization\ChiefUserProvider;
use Thinktomorrow\Chief\Admin\Nav\Nav;
use Thinktomorrow\Chief\Admin\Settings\SettingFields;
use Thinktomorrow\Chief\Admin\Settings\Settings;
use Thinktomorrow\Chief\Admin\Users\Application\EnableUser;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\SendInvite;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\App\Console\GenerateSitemap;
use Thinktomorrow\Chief\App\Http\Controllers\Back\System\SettingsController;
use Thinktomorrow\Chief\App\Listeners\LogSuccessfulLogin;
use Thinktomorrow\Chief\Forms\FormsServiceProvider;
use Thinktomorrow\Chief\Fragments\Actions\DeleteFragment;
use Thinktomorrow\Chief\Fragments\Actions\UpdateFragmentMetadata;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Events\FragmentAdded;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\AdminEnvironment;
use Thinktomorrow\Chief\Site\Urls\Application\CreateUrlForPage;
use Thinktomorrow\Squanto\SquantoManagerServiceProvider;
use Thinktomorrow\Squanto\SquantoServiceProvider;

class ChiefServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /*
         * ------------------------------------
         * Boot required for frontend
         * ------------------------------------
         */
        (new SquantoServiceProvider($this->app))->boot();
        (new RoutesServiceProvider($this->app))->boot();

        Relation::morphMap(['fragmentmodel' => FragmentModel::class]);

        if (! $this->app->make(AdminEnvironment::class)->check()) {
            return;
        }

        /*
         * ------------------------------------
         * Boot required for admin
         * ------------------------------------
         */
        $this->bootChiefAuth();
        $this->bootChiefSquanto();
        $this->bootEvents();

        (new ViewServiceProvider($this->app))->boot();
        (new FormsServiceProvider($this->app))->boot();
        (new SquantoManagerServiceProvider($this->app))->boot();
        (new AssetLibraryServiceProvider($this->app))->boot();
        (new SitemapServiceProvider($this->app))->boot();

        // Sitemap command is used by both cli and web scripts
        $this->commands(['command.chief:sitemap']);
        $this->app->bind('command.chief:sitemap', GenerateSitemap::class);

        if ($this->app->runningInConsole()) {
            (new ConsoleServiceProvider($this->app))->boot();
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/chief.php', 'chief');
        $this->mergeConfigFrom(__DIR__.'/../../config/chief-settings.php', 'chief-settings');

        if ($this->app->runningInConsole()) {
            (new ConsoleServiceProvider($this->app))->register();
        }

        $this->app->singleton(Registry::class, function () {
            return new Registry([]);
        });

        $this->app->singleton(Settings::class, function () {
            return new Settings();
        });

        (new SquantoServiceProvider($this->app))->register();

        if ($this->app->make(AdminEnvironment::class)->check()) {
            $this->app->when(SettingsController::class)
                ->needs(SettingFields::class)
                ->give(function () {
                    return new SettingFields(new Settings());
                })
            ;

            // Global chief nav singleton
            $this->app->singleton(Nav::class, function () {
                return new Nav();
            });

            (new SquantoManagerServiceProvider($this->app))->register();
            (new AssetLibraryServiceProvider($this->app))->register();
            (new SitemapServiceProvider($this->app))->register();
        }
    }

    private function bootChiefAuth(): void
    {
        $this->app['config']['auth.guards.chief'] = [
            'driver' => 'session',
            'provider' => 'chief',
        ];

        $this->app['config']['auth.providers.chief'] = [
            'driver' => 'chief-eloquent',
            'model' => User::class,
        ];

        $this->app['config']['auth.passwords.chief'] = [
            'provider' => 'chief',
            'table' => 'chief_password_resets',
            'expire' => 60,
        ];

        // Custom models for permission
        $this->app['config']['permission.models'] = [
            'permission' => \Thinktomorrow\Chief\Admin\Authorization\Permission::class,
            'role' => \Thinktomorrow\Chief\Admin\Authorization\Role::class,
        ];

        Auth::provider('chief-eloquent', function ($app, array $config) {
            return new ChiefUserProvider($app['hash'], $config['model']);
        });
    }

    private function bootChiefSquanto(): void
    {
        // Project specific squanto files
        $this->app['view']->addNamespace('squanto', __DIR__.'/../../resources/views/vendor/squanto');

        // Chief squanto defaults
        $this->app['view']->addNamespace('squanto', base_path().'/resources/views/vendor/thinktomorrow/chief/vendor/squanto');

        // Use the chief routing
        $this->app['config']['squanto.use_default_routes'] = false;
    }

    private function bootEvents(): void
    {
        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(UserInvited::class, SendInvite::class);
        Event::listen(InviteAccepted::class, EnableUser::class.'@onAcceptingInvite');

        Event::listen(ManagedModelCreated::class, CreateUrlForPage::class.'@onManagedModelCreated');
        Event::listen(FragmentDetached::class, DeleteFragment::class.'@onFragmentDetached');
        Event::listen(FragmentDetached::class, UpdateFragmentMetadata::class.'@onFragmentDetached');
        Event::listen(FragmentAdded::class, UpdateFragmentMetadata::class.'@onFragmentAdded');
        Event::listen(FragmentDuplicated::class, UpdateFragmentMetadata::class.'@onFragmentDuplicated');
    }
}
