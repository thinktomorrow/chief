<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait ShowsPageState
{
    public function pageStateAsLabel(): string
    {
        if (! $this instanceof StatefulContract) {
            return '';
        }

        if ($this instanceof Visitable) {
            if (LinkForm::fromModel($this)->isAnyLinkOnline()) {
                return '<span class="label label-xs label-success">Online</span>';
            }
            if (PageState::published === $this->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY)) {
                return '<span class="label label-xs label-info">Nog niet online. Er ontbreekt nog een link.</span>';
            }
            if (PageState::draft === $this->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY)) {
                return '<span class="label label-xs label-error">Offline</span>';
            }
        }

        if (PageState::published === $this->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY)) {
            return '<span class="label label-xs label-success">Gepubliceerd</span>';
        }

        if (PageState::draft === $this->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY)) {
            return '<span class="label label-xs label-error">In draft</span>';
        }

        if (PageState::archived === $this->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY)) {
            return '<span class="label label-xs label-warning">Gearchiveerd</span>';
        }

        return '';
    }
}
