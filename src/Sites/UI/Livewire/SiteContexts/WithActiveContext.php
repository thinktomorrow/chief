<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteContexts;

use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\ContextDto;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

trait WithActiveContext
{
    public function findActiveContext(string $site): ?ContextDto
    {
        $activeContextId = $this->activeContexts[$site] ?? null;

        if (! $activeContextId) {
            return null;
        }

        return $this->contexts->first(fn ($context) => $context->id === $activeContextId);
    }

    protected function refreshContexts(): void
    {
        $model = ModelReference::fromString($this->modelReference)->instance();
        $this->activeContexts = $model->getSiteContexts();
        $this->contexts = app(ComposeLivewireDto::class)->getContextsByOwner($model->modelReference());
    }
}
