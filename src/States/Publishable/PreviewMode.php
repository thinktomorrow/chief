<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\States\Publishable;

use Illuminate\Support\Str;

class PreviewMode
{
    private $active;

    // Default state for preview mode
    const DEFAULT = true;

    final public function __construct(bool $active)
    {
        $this->active = $active;
    }

    public static function fromRequest()
    {
        if(Str::startsWith(request()->path(), 'admin/')) return new static(false);

        $active = (session()->get('preview-mode', static::DEFAULT) === true && auth()->guard('chief')->check());

        return new static($active);
    }

    public static function toggle()
    {
        session()->put('preview-mode', !session()->get('preview-mode', static::DEFAULT));
    }

    public function check(): bool
    {
        return $this->active;
    }
}
