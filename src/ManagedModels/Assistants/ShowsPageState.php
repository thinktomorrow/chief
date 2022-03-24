<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;
use Thinktomorrow\Chief\Site\Urls\Links\LinkRepository;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait ShowsPageState
{
    public function pageStateAsLabel(): string
    {
        if (! $this instanceof WithPageState) {
            return '';
        }

        if ($this instanceof Visitable) {
            // TODO: check differently
            // TODO: should be a join relation when fetching the models... (list of online urls) so it can be used for preview as well
            // List of Link objects!!
//            $links =
//            $links = app(LinkRepository::class)->getOnlineLinksFor($this->modelReference()->shortClassName(), $this->modelReference()->id());

//            if (PageState::PUBLISHED === $this->getPageState() && count($this->getOnlineLinks()) > 0) {
//                return '<span class="label label-xs label-success">Online</span>';
//            }
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
