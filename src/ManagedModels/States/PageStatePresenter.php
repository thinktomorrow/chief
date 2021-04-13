<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States;

use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class PageStatePresenter
{
    /** @var StatefulContract */
    private $model;

    /** @var PageState */
    private $pageState;

    final public function __construct(StatefulContract $model, PageState $pageState)
    {
        $this->model = $model;
        $this->pageState = $pageState;
    }

    /**
     * @return static
     */
    public static function fromModel(StatefulContract $model): self
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
        $state = $this->model->stateOf($this->pageState::KEY);

        $labels = [
            PageState::DRAFT => 'offline',
            PageState::PUBLISHED => 'online',
            PageState::ARCHIVED => 'archief',
        ];

        return $labels[$state] ?? $state;
    }
}