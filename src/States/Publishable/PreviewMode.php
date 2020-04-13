<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\States\Publishable;

class PreviewMode
{
    private $active;

    final public function __construct(bool $active)
    {
        $this->active = $active;
    }

    public static function fromRequest()
    {
        $active = (session()->get('preview-mode') === true && auth()->guard('chief')->check());

        return new static($active);
    }

    public function check(): bool
    {
        return $this->active;
    }
}
