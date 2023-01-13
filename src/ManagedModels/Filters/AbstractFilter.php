<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Closure;
use Illuminate\Http\Request;

abstract class AbstractFilter
{
    protected string $type;
    protected string $queryKey;
    protected Closure $query;
    protected ?string $view = null;

    protected ?string $label;

    protected ?string $description = null;
    protected ?string $placeholder = null;

    /** @var null|mixed */
    protected $value;

    private $default = null;

    final public function __construct(string $type, string $queryKey, Closure $query)
    {
        $this->type = $type;
        $this->queryKey = $queryKey;
        $this->query = $query;

        $this->label = null;
        $this->description = $this->placeholder = $this->value = null;
    }

    public function applicable(Request $request): bool
    {
        return ($request->filled($this->queryKey) || $this->value);
    }

    public function queryKey(): string
    {
        return $this->queryKey;
    }

    public function query(): Closure
    {
        return $this->query;
    }

    public function view(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function default($default): self
    {
        $this->default = $default;

        return $this;
    }

    public function value(array $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function render(): string
    {
        $path = $this->view ?? 'chief::manager.filters.' . $this->type;

        return view($path, $this->viewData())->render();
    }

    protected function viewData(): array
    {
        return [
            'id' => $this->queryKey,
            'name' => $this->queryKey,
            'label' => $this->label,
            'description' => $this->description,
            'value' => old($this->queryKey, request()->input($this->queryKey, $this->value)),
            'placeholder' => $this->placeholder,
            'default' => $this->default,
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }
}
