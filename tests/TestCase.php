<?php

namespace Thinktomorrow\Chief\Tests;

use Bugsnag\BugsnagLaravel\BugsnagServiceProvider;
use Dimsav\Translatable\TranslatableServiceProvider;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Thinktomorrow\Chief\App\Exceptions\Handler;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thinktomorrow\Chief\App\Http\Kernel;
use Thinktomorrow\Chief\App\Http\Middleware\ChiefRedirectIfAuthenticated;
use Thinktomorrow\Chief\App\Providers\ChiefServiceProvider;
use Thinktomorrow\Chief\App\Providers\DemoServiceProvider;
use Thinktomorrow\Squanto\SquantoManagerServiceProvider;
use Thinktomorrow\Squanto\SquantoServiceProvider;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Thinktomorrow\AssetLibrary\AssetLibraryServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    use ChiefDatabaseTransactions,
        TestHelpers;

    protected $protectTestEnvironment = true;

    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,

            TranslatableServiceProvider::class,
            SquantoServiceProvider::class,
            SquantoManagerServiceProvider::class,
            ActivitylogServiceProvider::class,

            ChiefServiceProvider::class,

            // Demo is used for our preview testing
            DemoServiceProvider::class,
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->protectTestEnvironment();
        $this->registerResponseMacros();

        // Register the Chief Exception handler
        $this->app->singleton(
            ExceptionHandler::class,
            Handler::class
        );

        // Path is relative to root of phpunit execution.
        $this->withFactories(realpath(dirname(__DIR__).'/database/factories'));

        // Load database before overriding the config values but after the basic app setup
        $this->setUpDatabase();
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Kernel::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = realpath(__DIR__ . '/../');

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
        ]);

        // For our tests is it required to have 2 languages: nl and en.
        $app['config']->set('app.locale', 'nl'); // Default locale is considered nl
        $app['config']->set('translatable.locales', ['nl', 'en']);
        $app['config']->set('squanto.template', 'chief::back._layouts.master');

        $app['config']->set('activitylog.default_log_name', 'default');
        $app['config']->set('activitylog.default_auth_driver', 'chief');
        $app['config']->set('activitylog.activity_model', \Thinktomorrow\Chief\Common\Audit\Audit::class);

        // Override the guest middleware since this is overloaded by Orchestra testbench itself
        $app->bind(\Orchestra\Testbench\Http\Middleware\RedirectIfAuthenticated::class, ChiefRedirectIfAuthenticated::class);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }
            public function report(\Exception $e)
            {
            }
            public function render($request, \Exception $e)
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
}
