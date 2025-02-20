<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Routes;

final class ManagedRoute
{
    /** @var string */
    public $method;

    /** @var string */
    public $action;

    /** @var string */
    public $uri;

    private function __construct(string $method, string $action, string $uri)
    {
        $this->method = $method;
        $this->action = $action;
        $this->uri = $uri;
    }

    public static function get(string $action, ?string $uri = null): self
    {
        return new self('GET', $action, $uri ?? $action);
    }

    public static function post(string $action, ?string $uri = null): self
    {
        return new self('POST', $action, $uri ?? $action);
    }

    public static function put(string $action, ?string $uri = null): self
    {
        return new self('PUT', $action, $uri ?? $action);
    }

    public static function delete(string $action, ?string $uri = null): self
    {
        return new self('DELETE', $action, $uri ?? $action);
    }
}
