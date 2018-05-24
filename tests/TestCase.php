<?php

namespace Thinktomorrow\Chief\Tests;

use Bugsnag\BugsnagLaravel\BugsnagServiceProvider;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\App\Exceptions\Handler;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thinktomorrow\Chief\App\Providers\ChiefServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    use TestHelpers;

    protected $protectTestEnvironment = true;

    protected function getPackageProviders($app)
    {
        return [
            BugsnagServiceProvider::class,
            ChiefServiceProvider::class
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        // Load database before overriding the config values but after the basic app setup
        $this->setUpDatabase();

        // Path is relative to root of phpunit execution.
        $this->withFactories(realpath(dirname(__DIR__).'/database/factories'));

        $this->protectTestEnvironment();

        $this->registerResponseMacros();
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

        // Start session by default
        $app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware('Illuminate\Session\Middleware\StartSession');
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler{
            public function __construct(){}
            public function report(\Exception $e){}
            public function render($request, \Exception $e){ throw $e; }
        });
    }

    protected function disableCookiesEncryption(array $cookies)
    {
        $this->app->resolving(EncryptCookies::class,
            function ($object) use ($cookies) {
                foreach($cookies as $cookie) $object->disableFor($cookie);
            });

        return $this;
    }

    protected function protectTestEnvironment()
    {
        if( ! $this->protectTestEnvironment) return;

        if("testing" !== $this->app->environment())
        {
            throw new \Exception('Make sure your testing environment is properly set. You are now running tests in the ['.$this->app->environment().'] environment');
        }

        if(DB::getName() != "testing" && DB::getName() != "setup")
        {
            throw new \Exception('Make sure to use a dedicated testing database connection. Currently you are using ['.DB::getName().']. Are you crazy?');
        }
    }

}
