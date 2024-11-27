<?php

namespace Thinktomorrow\Chief\Table\Filters\Presets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagGroupRead;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models\NullTagGroup;
use Thinktomorrow\Chief\Table\Filters\SelectFilter;

class TagFilter extends SelectFilter
{
    /** @var string all|used|category */
    private string $optionType = 'all';

    private ?Collection $tags = null;
    private array $tagGroupIds = [];
    private array $ownerTypes = [];

    public static function makeDefault(string $resourceKey): self
    {
        return static::make('tags')
            ->multiple()
            ->filterByUsedTags()
            ->filterByOwnerTypes([$resourceKey])
            ->label('Tags')
            ->options(function ($filter) {

                return $filter->getTags()->map(function (TagRead $tagRead) {
                    return [
                        'label' => $tagRead->getLabel(),
                        'value' => $tagRead->getTagId(),
                    ];
                })->all();

                // WITH TAG GROUPS: (not yet supported) but should be a select list field instead of multiple

                //                $tagGroups = app(TagReadRepository::class)->getAllGroups()->sortBy('order');
                //                $tagGroups->push(NullTagGroup::fromMappedData([]));
                //
                //                $groupedTags = $filter->getTags()->groupBy(fn (TagRead $tagRead) => $tagRead->getTagGroupId());
                //
                //                $tagGroups = $tagGroups->reject(fn (TagGroupRead $tagGroupRead) => ! $groupedTags->has($tagGroupRead->getTagGroupId()))
                //                    ->map(function (TagGroupRead $tagGroupRead) use ($groupedTags) {
                //                        return [
                //                            'label' => $tagGroupRead->getLabel(),
                //                            'options' => $groupedTags->get($tagGroupRead->getTagGroupId())->map(function (TagRead $tagRead) {
                //                                return [
                //                                    'label' => $tagRead->getLabel(),
                //                                    'value' => $tagRead->getTagId(),
                //                                ];
                //                            })->all(),
                //                        ];
                //                    });
                //
                //                return PairOptions::toPairs($tagGroups->toArray());

            })->query(function ($builder, $value) {

                // Set to true enforce AND clause for filtering on multiple tags - default is OR
                $mustMatchAllTags = false;

                //                if (is_array($value) && reset($value) === 'none') {
                //                    $builder->doesnthave('tags');
                //
                //                    return;
                //                }

                $tagIds = (array) $value;

                if (count($tagIds) < 1) {
                    return;
                }

                if ($mustMatchAllTags) {
                    foreach ($tagIds as $tagId) {
                        $builder->whereHas('tags', function ($query) use ($tagId) {
                            $query->where('id', $tagId);
                        });
                    }
                } else {
                    $builder->whereHas('tags', function ($query) use ($tagIds) {
                        $query->whereIn('id', $tagIds);
                    });
                }
            });
    }

    public function filterByUsedTags(): static
    {
        $this->optionType = 'used';

        return $this;
    }

    public function filterByOwnerTypes(array|string $ownerTypes): static
    {
        $this->optionType = 'owner_type';
        $this->ownerTypes = (array)$ownerTypes;

        return $this;
    }

    public function filterByTagCategory(array|string|int $tagGroupIds): static
    {
        $this->optionType = 'category';
        $this->tagGroupIds = (array)$tagGroupIds;

        return $this;
    }

    private function getTags(): Collection
    {
        if (isset($this->tags)) {
            return $this->tags;
        }

        return match ($this->optionType) {
            'used' => app(TagReadRepository::class)->getAll()->reject(fn (TagRead $tagRead) => $tagRead->getUsages() < 1),
            'owner_type' => app(TagReadRepository::class)->getAll()->filter(function (TagRead $tagRead) {
                return $tagRead->getOwnerReferences()->contains(fn ($pivotRow) => in_array($pivotRow->owner_type, $this->ownerTypes));
            }),
            'category' => app(TagReadRepository::class)->getAll()->filter(fn (TagRead $tagRead) => in_array($tagRead->getTagGroupId(), $this->tagGroupIds)),
            default => app(TagReadRepository::class)->getAll(),
        };
    }
}
