<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    protected string $type;
    protected string $queryKey;
    protected Closure $query;
    protected ?string $view = null;

    protected ?string $label = null;
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

        $this->label = $this->description = $this->placeholder = $this->value = null;
    }

    public function applicable(array $parameterBag): bool
    {
        return ! is_null($this->extractParameterValue($parameterBag)) || $this->value;
    }

    public function queryKey(): string
    {
        return $this->queryKey;
    }

    public function query(Builder $builder, array $parameterBag): void
    {
        call_user_func_array($this->query, [
            $builder,
            $this->extractParameterValue($parameterBag),
            $parameterBag,
        ]);
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

    public function value($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function render(array $parameterBag): string
    {
        $path = $this->view ?? 'chief::manager.filters.' . $this->type;

        return view($path, $this->viewData($parameterBag))->render();
    }

    protected function extractParameterValue(array $parameterBag, $default = null)
    {
        return $parameterBag[$this->queryKey()] ?? $default;
    }

    protected function viewData(array $parameterBag): array
    {
        return [
            'id' => $this->queryKey,
            'name' => $this->queryKey,
            'label' => $this->label,
            'description' => $this->description,
            'value' => old($this->queryKey, $this->extractParameterValue($parameterBag, $this->value)),
            'placeholder' => $this->placeholder,
            'default' => $this->default,
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }
}
