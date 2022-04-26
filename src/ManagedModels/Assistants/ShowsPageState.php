<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\PageState\WithPageState;

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
            if (PageState::PUBLISHED === $this->getPageState()) {
                return '<span class="label label-xs label-info">Nog niet online. Er ontbreekt nog een link.</span>';
            }
            if (PageState::DRAFT === $this->getPageState()) {
                return '<span class="label label-xs label-error">Offline</span>';
            }
        }

        if (PageState::PUBLISHED === $this->getPageState()) {
            return '<span class="label label-xs label-success">Gepubliceerd</span>';
        }

        if (PageState::DRAFT === $this->getPageState()) {
            return '<span class="label label-xs label-error">In draft</span>';
        }

        if (PageState::ARCHIVED === $this->getPageState()) {
            return '<span class="label label-xs label-warning">Gearchiveerd</span>';
        }

        return '';
    }
}
