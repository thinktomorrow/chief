<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Publishable;

use Illuminate\Support\Str;

class PreviewMode
{
    private $active;

    final public function __construct(bool $active)
    {
        $this->active = $active;
    }

    public static function fromRequest()
    {
        if(!config('chief.preview-mode') || Str::startsWith(request()->path(), 'admin/')) {
            return new static(false);
        }

        $active = (session()->get('preview-mode', static::default()) === true && auth()->guard('chief')->check());

        return new static($active);
    }

    public static function toggle()
    {
        session()->put('preview-mode', !session()->get('preview-mode', static::default()));
    }

    public function check(): bool
    {
        return $this->active;
    }

    private static function default(): bool
    {
        $mode = config('chief.preview-mode');

        if(!$mode || $mode == 'live') return false;

        return ($mode == 'preview');
    }
}
