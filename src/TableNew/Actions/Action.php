<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Actions;

use Illuminate\Contracts\Support\Htmlable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Table\Concerns\HasView;

class Action extends \Illuminate\View\Component implements Htmlable
{
    use HasComponentRendering;
    use HasView;
    use HasCustomAttributes;
    use HasKey;
    use HasLabel;
    use HasDescription;

    use HasLocalizableProperties;

    public function __construct(string $key)
    {
        $this->key($key);
        $this->label($key);
    }

    public static function make(string|int|null $key)
    {
        return new static((string) $key);
    }
}
