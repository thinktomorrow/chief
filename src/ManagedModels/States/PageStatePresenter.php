<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States;

class PageStatePresenter
{
    private WithPageState $model;

    private PageState $pageState;

    final public function __construct(WithPageState $model, PageState $pageState)
    {
        $this->model = $model;
        $this->pageState = $pageState;
    }

    public static function fromModel(WithPageState $model): self
    {
        return new static($model, PageState::make($model));
    }

    public function label(): string
    {
        $class = $this->pageState->isOnline() ? 'text-success' : 'text-warning';

        return '<span class="inline-xs stack-s ' . $class . '">' . $this->stateAsLabel() . '</span>';
    }

    /**
     * Allowed transitions starting from this state
     *
     * @return array
     */
    public function transitions(): array
    {
        return $this->pageState->allowedTransitions();
    }

    private function stateAsLabel()
    {
        $state = $this->model->getPageState();

        $labels = [
            PageState::DRAFT => 'offline',
            PageState::PUBLISHED => 'online',
            PageState::ARCHIVED => 'archief',
        ];

        return $labels[$state] ?? $state;
    }
}
