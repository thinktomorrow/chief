<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Table;

use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Support\Htmlable;
use Thinktomorrow\Chief\Forms\Concerns\HasId;
use Thinktomorrow\Chief\Forms\Concerns\HasElementId;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use function view;

abstract class Component extends \Illuminate\View\Component implements Htmlable
{
    use HasComponentRendering;
    use HasKey;
    use HasId;
    use HasElementId;

    protected string $tableColumnView = 'chief-form::table.column';

    public function __construct(string $key)
    {
        $this->key($key);
        $this->id($key);

        $this->elementId($key.'_'.Str::random());
    }

    public static function make(string $key)
    {
        return new static($key);
    }

    public function render(): View
    {
        return view($this->tableColumnView, array_merge($this->data(), [
            'component' => $this,
        ]));
    }
}
