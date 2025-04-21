<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users;

use Illuminate\Session\Store;

class LocaleScope
{
    private Store $session;

    private static string $session_key = 'locale_scope';

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function set(string $locale): void
    {
        $this->session->put(static::$session_key, $locale);
    }

    public function get(): ?string
    {
        return $this->session->get(static::$session_key);
    }

    public function clear(): void
    {
        $this->session->forget(static::$session_key);
    }

    public function exists(): bool
    {
        return $this->session->has(static::$session_key);
    }
}
