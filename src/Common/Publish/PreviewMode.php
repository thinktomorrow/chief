<?php

namespace Thinktomorrow\Chief\Common\Publish;

class PreviewMode
{
    private $active;

    public function __construct(bool $active)
    {
        $this->active = $active;
    }

    public static function fromRequest()
    {
        $active = (request()->has('preview-mode') && auth()->guard('chief')->check());

        return new static($active);
    }

    public function check(): bool
    {
        return $this->active;
    }
}