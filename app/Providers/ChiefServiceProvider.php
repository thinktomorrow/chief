<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\Sitemap\SitemapServiceProvider;
use Thinktomorrow\Chief\Admin\Authorization\ChiefUserProvider;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Admin\Nav\Nav;
use Thinktomorrow\Chief\Admin\Settings\SettingFields;
use Thinktomorrow\Chief\Admin\Settings\Settings;
use Thinktomorrow\Chief\Admin\Users\Application\EnableUser;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\SendInvite;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\App\Console\GenerateImageSitemap;
use Thinktomorrow\Chief\App\Console\GenerateSitemap;
use Thinktomorrow\Chief\App\Http\Controllers\Back\System\SettingsController;
use Thinktomorrow\Chief\App\Listeners\LogSuccessfulLogin;
use Thinktomorrow\Chief\Assets\AssetsServiceProvider;
use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\Forms\FormsServiceProvider;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFirstContextForPage;
use Thinktomorrow\Chief\Fragments\App\Actions\DeleteFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\UpdateFragmentMetadata;
use Thinktomorrow\Chief\Fragments\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Events\FragmentsReordered;
use Thinktomorrow\Chief\Fragments\Events\FragmentUpdated;
use Thinktomorrow\Chief\Fragments\FragmentsServiceProvider;
use Thinktomorrow\Chief\ManagedModels\Actions\DeleteModel;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelArchived;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelDeleted;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelQueuedForDeletion;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUnPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\ManagedModels\Listeners\PropagateArchivedUrl;
use Thinktomorrow\Chief\ManagedModels\Listeners\TriggerPageChangedEvent;
use Thinktomorrow\Chief\ManagedModels\States\StatesServiceProvider;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Menu\App\Actions\ProjectModelData;
use Thinktomorrow\Chief\Menu\MenuServiceProvider;
use Thinktomorrow\Chief\Shared\AdminEnvironment;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\PropagateUrlChange;
use Thinktomorrow\Chief\Site\Urls\Application\CreateUrlForPage;
use Thinktomorrow\Chief\Sites\SitesServiceProvider;
use Thinktomorrow\Chief\Table\TableServiceProvider;
use Thinktomorrow\Squanto\SquantoManagerServiceProvider;
use Thinktomorrow\Squanto\SquantoServiceProvider;

class ChiefServiceProvider extends ServiceProvider
{
    private SitemapServiceProvider $sitemapServiceProvider;

    // TODO: use this list to loop over all SP. Each SP should have boot method for frontend essentials
    // and a bootAdmin for the admin booting. Also a register and registerAdmin method. This allows
    // To make distinction per provider instead of doing this all here in the main service provider.
    // NOt in use yet... perhaps use a ChiefServiceProviderInterface for this.
    private array $internalProviders = [
        SitesServiceProvider::class,
        StatesServiceProvider::class,
        SquantoServiceProvider::class,
        RoutesServiceProvider::class,
        FragmentsServiceProvider::class,
        MenuServiceProvider::class,
        SquantoManagerServiceProvider::class,
        AssetsServiceProvider::class,
    ];

    public function __construct($app)
    {
        parent::__construct($app);

        // Spatie Package logic sets a Package property on register so this needs to be retained when calling boot as well
        $this->sitemapServiceProvider = new SitemapServiceProvider($app);
    }

    public function boot(): void
    {
        /*
         * ------------------------------------
         * Boot required for frontend
         * ------------------------------------
         */
        $this->bootEssentials();

        if (! $this->app->make(AdminEnvironment::class)->check(request())) {
            return;
        }

        /*
         * ------------------------------------
         * Boot required for admin
         * ------------------------------------
         */
        $this->bootChiefSquanto();
        $this->bootEvents();

        (new ViewServiceProvider($this->app))->boot();
        (new FormsServiceProvider($this->app))->boot();
        (new FragmentsServiceProvider($this->app))->bootAdmin();
        (new MenuServiceProvider($this->app))->bootAdmin();
        (new TableServiceProvider($this->app))->boot();
        (new AssetsServiceProvider($this->app))->boot();
        (new SquantoManagerServiceProvider($this->app))->boot();
        $this->sitemapServiceProvider->boot();

        // Sitemap command is used by both cli and web scripts
        $this->commands(['command.chief:sitemap']);
        $this->commands(['command.chief:image-sitemap']);
        $this->app->bind('command.chief:sitemap', GenerateSitemap::class);
        $this->app->bind('command.chief:image-sitemap', GenerateImageSitemap::class);

        if ($this->app->runningInConsole()) {
            (new ConsoleServiceProvider($this->app))->boot();
        }
    }

    private function bootEssentials()
    {
        (new SitesServiceProvider($this->app))->boot();
        (new StatesServiceProvider($this->app))->boot();
        (new SquantoServiceProvider($this->app))->boot();
        (new RoutesServiceProvider($this->app))->boot();

        $this->bootChiefAuth();

        (new FragmentsServiceProvider($this->app))->boot();
        (new MenuServiceProvider($this->app))->boot();
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
            'permission' => Permission::class,
            'role' => Role::class,
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
        // User events
        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(UserInvited::class, SendInvite::class);
        Event::listen(InviteAccepted::class, EnableUser::class.'@onAcceptingInvite');

        // Managed model events
        Event::listen(ManagedModelCreated::class, [CreateUrlForPage::class, 'onManagedModelCreated']);
        Event::listen(ManagedModelCreated::class, [CreateFirstContextForPage::class, 'onManagedModelCreated']);
        Event::listen(ManagedModelUrlUpdated::class, [TriggerPageChangedEvent::class, 'onManagedModelUrlUpdated']);
        Event::listen(ManagedModelUrlUpdated::class, [ProjectModelData::class, 'onManagedModelUrlUpdated']);
        Event::listen(ManagedModelUrlUpdated::class, [PropagateUrlChange::class, 'onManagedModelUrlUpdated']);
        Event::listen(ManagedModelUpdated::class, [TriggerPageChangedEvent::class, 'onManagedModelUpdated']);
        Event::listen(ManagedModelUpdated::class, [ProjectModelData::class, 'onManagedModelUpdated']);
        Event::listen(ManagedModelArchived::class, [PropagateArchivedUrl::class, 'onManagedModelArchived']);
        Event::listen(ManagedModelArchived::class, [ProjectModelData::class, 'onManagedModelArchived']);
        Event::listen(ManagedModelPublished::class, [ProjectModelData::class, 'onManagedModelPublished']);
        Event::listen(ManagedModelUnPublished::class, [ProjectModelData::class, 'onManagedModelUnPublished']);
        Event::listen(ManagedModelQueuedForDeletion::class, [DeleteModel::class, 'onManagedModelQueuedForDeletion']);
        Event::listen(ManagedModelDeleted::class, [TriggerPageChangedEvent::class, 'onManagedModelDeleted']);
        Event::listen(ManagedModelDeleted::class, [ProjectModelData::class, 'onManagedModelDeleted']);

        // Fragment events
        Event::listen(FragmentDetached::class, [TriggerPageChangedEvent::class, 'onFragmentDetached']);
        Event::listen(FragmentDetached::class, [DeleteFragment::class, 'onFragmentDetached']);
        Event::listen(FragmentDetached::class, [UpdateFragmentMetadata::class, 'onFragmentDetached']);
        Event::listen(FragmentAttached::class, [TriggerPageChangedEvent::class, 'onFragmentAdded']);
        Event::listen(FragmentAttached::class, [UpdateFragmentMetadata::class, 'onFragmentAdded']);
        Event::listen(FragmentUpdated::class, [TriggerPageChangedEvent::class, 'onFragmentUpdated']);
        Event::listen(FragmentDuplicated::class, [TriggerPageChangedEvent::class, 'onFragmentDuplicated']);
        Event::listen(FragmentDuplicated::class, [UpdateFragmentMetadata::class, 'onFragmentDuplicated']);
        Event::listen(FragmentsReordered::class, [TriggerPageChangedEvent::class, 'onFragmentsReordered']);

        // Form events
        Event::listen(FormUpdated::class, [TriggerPageChangedEvent::class, 'onFormUpdated']);
        Event::listen(FormUpdated::class, [ProjectModelData::class, 'onFormUpdated']);
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
            return new Settings;
        });

        $this->app->bind(NestableRepository::class, MemoizedMysqlNestableRepository::class);

        (new SitesServiceProvider($this->app))->register();
        (new StatesServiceProvider($this->app))->register();
        (new SquantoServiceProvider($this->app))->register();
        (new FragmentsServiceProvider($this->app))->register();
        (new MenuServiceProvider($this->app))->register();

        if ($this->app->make(AdminEnvironment::class)->check(request())) {
            $this->app->when(SettingsController::class)
                ->needs(SettingFields::class)
                ->give(function () {
                    return new SettingFields(new Settings);
                });
            Relation::morphMap(['chiefuser' => User::class]);

            // Global chief nav singleton
            $this->app->singleton(Nav::class, function () {
                return new Nav;
            });

            (new AssetsServiceProvider($this->app))->register();
            (new SquantoManagerServiceProvider($this->app))->register();
            $this->sitemapServiceProvider->register();
        }
    }
}
