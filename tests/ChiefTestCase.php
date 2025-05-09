<?php

namespace Thinktomorrow\Chief\Tests;

use Astrotomic\Translatable\TranslatableServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Image;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Svg;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Video;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Webp;
use Spatie\Permission\PermissionServiceProvider;
use Thinktomorrow\Chief\App\Exceptions\ChiefExceptionHandler;
use Thinktomorrow\Chief\App\Http\Kernel;
use Thinktomorrow\Chief\App\Providers\ChiefServiceProvider;
use Thinktomorrow\Chief\Fragments\ActiveContextId;
use Thinktomorrow\Chief\Shared\Helpers\Memoize;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Table\TableServiceProvider;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\TestHelpers;
use Thinktomorrow\Chief\Tests\Shared\TestingWithManagers;

use function Orchestra\Testbench\default_skeleton_path;

abstract class ChiefTestCase extends OrchestraTestCase
{
    use RefreshDatabase;
    use TestHelpers;
    use TestingWithManagers;

    protected $protectTestEnvironment = true;

    protected function resolveApplication()
    {
        return (new ApplicationBuilder(new Application(default_skeleton_path())))
            ->withProviders()
            ->withMiddleware(static function ($middleware) {
                $middleware->redirectGuestsTo('/admin/login');
            })
            ->withCommands()
            ->create();
    }

    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
            TranslatableServiceProvider::class,
            ActivitylogServiceProvider::class,
            ChiefServiceProvider::class,
            LivewireServiceProvider::class,
            TableServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            // Code after application created.
        });

        $this->beforeApplicationDestroyed(function () {
            // Code before application destroyed.
        });

        parent::setUp();

        $this->protectTestEnvironment();
        $this->registerResponseMacros();

        // Register the Chief Exception handler
        $this->app->singleton(ExceptionHandler::class, ChiefExceptionHandler::class);

        Factory::guessFactoryNamesUsing(fn (string $modelName) => 'Thinktomorrow\\Chief\\Database\\Factories\\'.class_basename($modelName).'Factory');

        $this->setUpChiefEnvironment();

        // Set nl as default locale for testing env
        config()->set('app.fallback_locale', 'nl');

        config()->set('chief.sites', [
            ['locale' => 'nl'],
            ['locale' => 'en'],
        ]);

        ChiefSites::clearCache();

        $this->app['view']->addLocation(__DIR__.'/Shared/stubs/views');

        // Fake storage local disk
        Storage::fake('local');
    }

    protected function tearDown(): void
    {
        SnippetStub::resetFieldsDefinition();
        ArticlePageResource::resetFieldsDefinition();
        \Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets::resetFieldsDefinition();

        // Clear out any memoized values
        Memoize::clear();

        ChiefSites::clearCache();

        ActiveContextId::clear();

        parent::tearDown();
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(HttpKernel::class, Kernel::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->recurse_copy($this->getStubDirectory(), $this->getTempDirectory());
        $app['path.base'] = realpath(__DIR__.'/../');

        $app->bind('path.public', function () {
            return $this->getTempDirectory();
        });

        // Livewire file upload disk tmp directory

        $app['config']->set('permission.table_names', [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ]);

        // Setup default database to use sqlite :memory:
        $app['config']->set('auth.defaults', [
            'guard' => 'xxx',
            'passwords' => 'chief',
        ]);

        // Connection is defined in the phpunit config xml
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', __DIR__.'/../database/testing.sqlite'),
            'prefix' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        // For our tests is it required to have 2 languages: nl and en.
        $app['config']->set('app.locale', 'nl'); // Default locale is considered nl
        $app['config']->set('chief.locales.admin', ['nl', 'en']);
        $app['config']->set('squanto', require $this->getTempDirectory('config/squanto.php'));

        $app['config']->set('activitylog.default_log_name', 'default');
        $app['config']->set('activitylog.default_auth_driver', 'chief');
        $app['config']->set('activitylog.activity_model', \Thinktomorrow\Chief\Admin\Audit\Audit::class);

        $app['config']->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => $this->getMediaDirectory(),
        ]);
        $app['config']->set('filesystems.disks.secondMediaDisk', [
            'driver' => 'local',
            'root' => $this->getTempDirectory('media2'),
        ]);

        // Livewire testing disk for file uploads
        $app['config']->set('filesystems.disks.tmp-for-tests', [
            'driver' => 'local',
            'root' => $this->getTempDirectory('livewire-tmp'), // Make sure it is livewire-tmp so it matches hardcoded default in Livewire
        ]);

        $app['config']->set('media-library.image_generators', [
            Image::class,
            Webp::class,
            Svg::class,
            Video::class,
        ]);

        $app['config']->set('thinktomorrow.assetlibrary.conversions', [
            'placeholder' => [
                'width' => 16,
                'height' => 16,
            ],
        ]);

        $app['config']->set('thinktomorrow.assetlibrary.formats', []);

        // Override the guest middleware since this is overloaded by Orchestra testbench itself
        // $app->bind(\Orchestra\Testbench\Http\Middleware\RedirectIfAuthenticated::class, ChiefRedirectIfAuthenticated::class);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends ChiefExceptionHandler
        {
            public function __construct() {}

            public function report(\Throwable $e) {}

            public function render($request, \Throwable $e)
            {
                throw $e;
            }
        });
    }

    protected function disableCookiesEncryption(array $cookies)
    {
        $this->app->resolving(EncryptCookies::class, function ($object) use ($cookies) {
            foreach ($cookies as $cookie) {
                $object->disableFor($cookie);
            }
        });

        return $this;
    }

    protected function protectTestEnvironment()
    {
        if (! $this->protectTestEnvironment) {
            return;
        }

        if ($this->app->environment() !== 'testing') {
            throw new \Exception('Make sure your testing environment is properly set. You are now running tests in the ['.$this->app->environment().'] environment');
        }

        if (DB::getName() != 'testing' && DB::getName() != 'setup') {
            throw new \Exception('Make sure to use a dedicated testing database connection. Currently you are using ['.DB::getName().']. Are you crazy?');
        }
    }

    private function getStubDirectory($dir = null)
    {
        return __DIR__.'/Shared/stubs/'.$dir;
    }

    protected function getTempDirectory($dir = null)
    {
        return __DIR__.'/Shared/Tmp/'.$dir;
    }

    public function getMediaDirectory($suffix = '')
    {
        return $this->getTempDirectory().'/media'.($suffix == '' ? '' : '/'.$suffix);
    }
}
