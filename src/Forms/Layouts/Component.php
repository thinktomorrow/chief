<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasId;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;
use Thinktomorrow\Chief\Forms\Concerns\HasView;

abstract class Component extends \Illuminate\View\Component implements Htmlable
{
    use HasComponentRendering;
    use HasComponents;
    use HasCustomAttributes;
    use HasDescription;
    use HasId;
    use HasTitle;
    use HasView;

    public function __construct(?string $id = null)
    {
        if (! $id) {
            $id = static::generateRandomId();
        }

        $this->id($id);
    }

    public static function make(?string $id = null)
    {
        return new static($id);
    }

    private static function generateRandomId(): string
    {
        return Str::random(10);
    }
}
