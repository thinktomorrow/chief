<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $protectTestEnvironment = true;

    protected function setUp()
    {
        parent::setUp();

        $this->protectTestEnvironment();

        //$this->registerResponseMacros();
    }

    // Override default createApplication
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        Hash::setRounds(4);

        return $app;
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
