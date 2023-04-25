<?php

namespace Thinktomorrow\Chief\Plugins\Tags\App\Presets;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\ManagedModels\Filters\Filter;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;

class TagsFilter implements Filter
{
    protected string $type;
    protected string $queryKey;
    protected ?Closure $query;
    protected ?string $view = null;

    protected ?string $label;

    protected ?string $description = null;
    protected ?string $placeholder = null;

    /** @var null|mixed */
    protected $value;

    private $default = null;

    /** @var string all|used|category */
    private string $optionType;

    private Collection $tags;
    private array $tagGroupIds = [];

    final public function __construct(string $queryKey = 'tags', ?Closure $query = null)
    {
        $this->queryKey = $queryKey;
        $this->query = $query;
        $this->optionType = 'used';
        $this->tags = collect();

        $this->label = $this->description = $this->placeholder = $this->value = null;
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
        return function (Builder $builder, $value) {
            $builder->whereHas('tags', function ($query) use ($value) {
                $query->whereIn('id', (array) $value);
            });
        };
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

    public function default($default): self
    {
        $this->default = $default;

        return $this;
    }

    public function render(): string
    {
        $path = $this->view ?? 'chief-tags::.filters.tags';

        return view($path, $this->viewData())->render();
    }

    public function tags(Collection $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function filterByUsedTags(): static
    {
        $this->optionType = 'used';

        return $this;
    }

    public function filterByTagCategory(array|string|int $tagGroupIds): static
    {
        $this->optionType = 'category';
        $this->tagGroupIds = (array) $tagGroupIds;

        return $this;
    }

    private function getTags(): Collection
    {
        if(! $this->tags->isEmpty()) {
            return $this->tags;
        }

        return match($this->optionType) {
            'used' => app(TagReadRepository::class)->getAll()->reject(fn (TagRead $tagRead) => $tagRead->getUsages() < 1),
            'category' => app(TagReadRepository::class)->getAll()->filter(fn (TagRead $tagRead) => in_array($tagRead->getTagGroupId(), $this->tagGroupIds)),
            default => app(TagReadRepository::class)->getAll(),
        };

    }

    protected function viewData(): array
    {
        return [
            'id' => $this->queryKey,
            'name' => $this->queryKey,
            'label' => $this->label,
            'description' => $this->description,
            'value' => old($this->queryKey, request()->input($this->queryKey, $this->value)),
            'default' => $this->default,
            'tags' => $this->getTags(),
        ];
    }
}
