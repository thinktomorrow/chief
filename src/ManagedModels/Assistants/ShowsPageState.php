<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\PageState\WithPageState;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait ShowsPageState
{
    public function pageStateAsLabel(): string
    {
        if (! $this instanceof WithPageState) {
            return '';
        }

        if ($this instanceof Visitable) {
            if (LinkForm::fromModel($this)->isAnyLinkOnline()) {
                return '<span class="label label-xs label-success">Online</span>';
            }
            if (PageState::published === $this->getPageState()) {
                return '<span class="label label-xs label-info">Nog niet online. Er ontbreekt nog een link.</span>';
            }
            if (PageState::draft === $this->getPageState()) {
                return '<span class="label label-xs label-error">Offline</span>';
            }
        }

        if (PageState::published === $this->getPageState()) {
            return '<span class="label label-xs label-success">Gepubliceerd</span>';
        }

        if (PageState::draft === $this->getPageState()) {
            return '<span class="label label-xs label-error">In draft</span>';
        }

        if (PageState::archived === $this->getPageState()) {
            return '<span class="label label-xs label-warning">Gearchiveerd</span>';
        }

        return '';
    }
}
