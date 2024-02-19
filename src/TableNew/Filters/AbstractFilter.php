<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Closure;

abstract class AbstractFilter
{
    protected string $queryKey;
    protected Closure $query;
    protected string $view;

    protected ?string $label;
    protected ?string $description = null;
    protected ?string $placeholder = null;
    protected $value = null;
    private $default = null;

    final public function __construct(string $queryKey, Closure $query)
    {
        $this->queryKey = $queryKey;
        $this->query = $query;

        $this->label = null;
        $this->description = $this->placeholder = $this->value = null;
    }

    public function queryKey(): string
    {
        return $this->queryKey;
    }

    public function query(): Closure
    {
        return $this->query;
    }

    public function view(string $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function placeholder(string $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function default($default): static
    {
        $this->default = $default;

        return $this;
    }

    public function value($value): static
    {
        $this->value = $value;

        return $this;
    }

    public function render(): string
    {
        return view($this->view, $this->viewData())->render();
    }

    public function getValue()
    {
        return old($this->queryKey, request()->input($this->queryKey, $this->value));
    }

    protected function viewData(): array
    {
        return [
            'id' => $this->queryKey,
            'name' => $this->queryKey,
            'label' => $this->label,
            'description' => $this->description,
            'value' => $this->getValue(),
            'placeholder' => $this->placeholder,
            'default' => $this->default,
        ];
    }
}
