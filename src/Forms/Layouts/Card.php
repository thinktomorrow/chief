<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasCollapsible;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasLayoutVariant;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;

class Card extends LayoutComponent implements Layout
{
    use HasCollapsible;
    use HasDescription;
    use HasLayoutVariant;
    use HasTitle;

    protected string $view = 'chief-form::layouts.card';

    protected string $previewView = 'chief-form::previews.layouts.card';

    protected function wireableMethods(array $components): array
    {
        return array_merge(parent::wireableMethods($components), [
            ...(isset($this->collapsed) ? ['collapsed' => $this->collapsed] : []),
            ...(isset($this->collapsible) ? ['collapsible' => $this->collapsible] : []),
            ...(isset($this->description) ? ['description' => $this->description] : []),
            ...(isset($this->layoutVariant) ? ['layoutVariant' => $this->layoutVariant] : []),
            ...(isset($this->title) ? ['title' => $this->title] : []),
        ]);
    }
}
