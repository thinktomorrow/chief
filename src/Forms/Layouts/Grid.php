<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasColumns;

class Grid extends LayoutComponent implements Layout
{
    use HasColumns;

    protected string $view = 'chief-form::layouts.grid';

    protected string $previewView = 'chief-form::previews.layouts.grid';

    protected function wireableMethods(array $components): array
    {
        return array_merge(parent::wireableMethods($components), [
            ...(isset($this->columns) ? ['columns' => $this->columns] : []),
        ]);
    }
}
