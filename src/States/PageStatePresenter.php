<?php

namespace Thinktomorrow\Chief\States;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\States\State\StatefulContract;

class PageStatePresenter
{
    /** @var StatefulContract */
    private $model;

    /** @var PageState */
    private $pageState;

    public function __construct(StatefulContract $model, PageState $pageState)
    {
        $this->model = $model;
        $this->pageState = $pageState;
    }

    public static function fromModel(StatefulContract $model)
    {
        return new static($model, new PageState($model));
    }

    public function render(): string
    {
        $class = $this->pageState->isOnline() ? 'text-success' : 'text-warning';

        return '<span class="inline-xs stack-s '.$class.'">' . $this->label() . '</span>';
    }

    private function label()
    {
        $state = $this->model->state();

        $labels = [
            PageState::DRAFT => 'offline',
            PageState::PUBLISHED => 'online',
            PageState::ARCHIVED => 'archief',
        ];

        return $labels[$state] ?? $state;
    }
}
