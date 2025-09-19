<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasId;
use Thinktomorrow\Chief\Forms\Concerns\HasPosition;
use Thinktomorrow\Chief\Forms\Concerns\HasView;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Tags\HasTaggedComponents;
use Thinktomorrow\Chief\Forms\Tags\WithTaggedComponents;
use Thinktomorrow\Chief\Forms\Tags\WithTags;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithWireableFieldDefaults;

abstract class LayoutComponent extends \Illuminate\View\Component implements HasTaggedComponents, Htmlable, Wireable
{
    use HasComponentRendering;
    use HasId;
    use HasKey;
    use HasModel;
    use HasPosition;
    use HasView;
    use WithTaggedComponents;
    use WithTags;
    use WithWireableFieldDefaults;

    public function __construct(?string $key = null)
    {
        if (! $key) {
            $key = Str::random(10);
        }

        $this->key = $key;
        $this->id($key);
    }

    public static function make(?string $id = null)
    {
        return new static($id);
    }

    protected function wireableMethods(array $components): array
    {
        return [
            ...['key' => $this->key],
            ...(isset($this->id) ? ['id' => $this->id] : []),
            ...(isset($this->components) ? ['components' => $components] : []),
            ...(isset($this->view) ? ['setView' => $this->view] : []),
            ...(isset($this->previewView) ? ['previewView' => $this->previewView] : []),
            ...(isset($this->position) ? ['position' => $this->position] : []),
            ...(isset($this->tags) ? ['tag' => $this->tags] : []),
        ];
    }
}
