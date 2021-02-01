<?php

namespace Thinktomorrow\Chief\Tests;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\App\Http\Kernel;
use Thinktomorrow\Chief\App\Exceptions\Handler;
use Thinktomorrow\Chief\Shared\Helpers\Memoize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Thinktomorrow\Chief\Site\Urls\MemoizedUrlRecord;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Spatie\Permission\PermissionServiceProvider;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\MediaLibrary\ImageGenerators\FileTypes\Svg;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;
use Spatie\MediaLibrary\ImageGenerators\FileTypes\Webp;
use Astrotomic\Translatable\TranslatableServiceProvider;
use Spatie\MediaLibrary\ImageGenerators\FileTypes\Image;
use Spatie\MediaLibrary\ImageGenerators\FileTypes\Video;
use Thinktomorrow\Chief\App\Providers\ChiefServiceProvider;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefRedirectIfAuthenticated;

abstract class ChiefTestCase extends OrchestraTestCase
{
    use RefreshDatabase;
    use TestHelpers;

    protected $protectTestEnvironment = true;

    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
            TranslatableServiceProvider::class,
            ActivitylogServiceProvider::class,
            ChiefServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->protectTestEnvironment();
        $this->registerResponseMacros();

        // Register the Chief Exception handler
        $this->app->singleton(
            ExceptionHandler::class,
            Handler::class
        );

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Thinktomorrow\\Chief\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->setUpChiefEnvironment();

        // Set nl as default locale for testing env
        config()->set('app.fallback_locale', 'nl');
    }

    protected function tearDown(): void
    {
        ManagerFactory::clearTemporaryFiles();
        ManagedModelFactory::clearTemporaryFiles();

        // Clear out any memoized values
        Memoize::clear();
        MemoizedUrlRecord::clearCachedRecords();

        parent::tearDown();
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Kernel::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->recurse_copy($this->getStubDirectory(), $this->getTempDirectory());
        $app['path.base'] = realpath(__DIR__ . '/../');

        $app->bind('path.public', function () {
            return $this->getTempDirectory();
        });

        $app['config']->set('permission.table_names', [
            'roles'                 => 'roles',
            'permissions'           => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles'       => 'model_has_roles',
            'role_has_permissions'  => 'role_has_permissions',
        ]);

        // Setup default database to use sqlite :memory:
        $app['config']->set('auth.defaults', [
            'guard'     => 'xxx',
            'passwords' => 'chief',
        ]);

        // Connection is defined in the phpunit config xml
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE', __DIR__.'/../database/testing.sqlite'),
            'prefix'   => '',
        ]);

        // For our tests is it required to have 2 languages: nl and en.
        $app['config']->set('app.locale', 'nl'); // Default locale is considered nl
        $app['config']->set('chief.locales', ['nl', 'en']);
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

        $app['config']->set('medialibrary.image_generators', [
            Image::class,
            Webp::class,
            Svg::class,
            Video::class,
        ]);

        // Override the guest middleware since this is overloaded by Orchestra testbench itself
        $app->bind(\Orchestra\Testbench\Http\Middleware\RedirectIfAuthenticated::class, ChiefRedirectIfAuthenticated::class);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }
            public function report(\Throwable $e)
            {
            }
            public function render($request, \Throwable $e)
            {
                throw $e;
            }
        });
    }

    protected function disableCookiesEncryption(array $cookies)
    {
        $this->app->resolving(EncryptCookies::class,
            function ($object) use ($cookies) {
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

        if ("testing" !== $this->app->environment()) {
            throw new \Exception('Make sure your testing environment is properly set. You are now running tests in the ['.$this->app->environment().'] environment');
        }

        if (DB::getName() != "testing" && DB::getName() != "setup") {
            throw new \Exception('Make sure to use a dedicated testing database connection. Currently you are using ['.DB::getName().']. Are you crazy?');
        }
    }

    protected function getResponseData($response, $key)
    {
        return $response->getOriginalContent()->getData()[$key];
    }

    private function getStubDirectory($dir = null)
    {
        return __DIR__ . '/Shared/stubs/' . $dir;
    }

    private function getTempDirectory($dir = null)
    {
        return __DIR__ . '/Shared/tmp/' . $dir;
    }

    public function getMediaDirectory($suffix = '')
    {
        return $this->getTempDirectory().'/media'.($suffix == '' ? '' : '/'.$suffix);
    }
}
