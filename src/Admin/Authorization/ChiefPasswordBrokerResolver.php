<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ChiefPasswordBrokerResolver
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function resolve(): ChiefPasswordBroker
    {
        $config = $this->getConfig('chief');

        if (is_null($config)) {
            throw new InvalidArgumentException('Password resetter [chief] is not defined.');
        }

        return new ChiefPasswordBroker($this->createTokenRepository($config), $this->app['auth']->createUserProvider($config['provider'] ?? null));
    }

    protected function createTokenRepository(array $config): DatabaseTokenRepository
    {
        $key = $this->app['config']['app.key'];

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $connection = $config['connection'] ?? null;

        return new DatabaseTokenRepository($this->app['db']->connection($connection), $this->app['hash'], $config['table'], $key, $config['expire']);
    }

    private function getConfig(string $name)
    {
        return $this->app['config']["auth.passwords.{$name}"];
    }
}
