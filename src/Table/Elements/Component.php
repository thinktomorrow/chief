<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Table\Concerns\HasHint;
use Thinktomorrow\Chief\Table\Concerns\HasView;

abstract class Component extends \Illuminate\View\Component implements Htmlable
{
    // Cell values
    use HasLocalizableProperties;
    use HasComponentRendering;
    use HasView;
    use HasComponents;
    use HasCustomAttributes;
    use HasHint;

    // Header values
    use HasKey;
    use HasTitle;
    use HasDescription;

    public function __construct(string $key)
    {
        $this->key($key);
        $this->title($key);
    }

    public static function make(string|int|null $key)
    {
        return new static((string) $key);
    }

    public function render(): View
    {
        $view = $this->getView();

        return view($view, array_merge($this->data(), [
            'component' => $this,
        ]));
    }
}
