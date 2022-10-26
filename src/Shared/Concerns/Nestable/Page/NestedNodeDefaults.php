<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use function collect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;

/**
 * Model logic for handling nesting based on the parent_id construct
 */
trait NestedNodeDefaults
{
    protected string $parentKeyName = 'parent_id';

//    public static function boot()
//    {
//        parent::boot();
//
//        static::saving(function (self $model) {
//            if($model->exists && $model->isDirty(static::$parentKeyName)) {
//                app(PropagateUrlChange::class)->handle($model);
//            }
//        });
//    }

    public function getId(): string
    {
        return $this->getNodeId();
    }

    public function getModel(): Model
    {
        return $this->getNodeEntry();
    }

    public function showOnline(): bool
    {
        if ($this->getNodeEntry() instanceof StatefulContract) {
            return $this->getNodeEntry()->inOnlineState();
        }

        return ! ! $this->getNodeEntry('show_online');
    }

    public function getUrlSlug(string $locale): ?string
    {
        return $this->getLocalizedModelValue('slug', $locale);
    }

    public function getLabel(string $locale): string
    {
        return $this->getLocalizedModelValue('title', $locale, '');
    }

    public function getChildren(): Collection
    {
        return collect($this->getChildNodes()->all());
    }

    public function getOnlineChildren(): Collection
    {
        return $this->getChildren()
            ->reject(fn (NestedNode $childNode) => ! $childNode->showOnline());
    }

//    public function getDirectChildrenIds(): array
//    {
//        return $this->getChildren()->map(fn(Nestable $child) => $child->getId())->toArray();
//    }
//
//    public function getAllChildrenIds(): array
//    {
//        return $this->pluckChildNodes('getId');
//    }

    public function getBreadCrumbs(): array
    {
        return $this->getAncestorNodes()->all();
    }

    public function getBreadCrumbLabelWithoutRoot(string $locale): string
    {
        return $this->getBreadcrumbLabel($locale, true);
    }

    public function getBreadCrumbLabel(string $locale, bool $withoutRoot = false): string
    {
        $label = $this->getLabel($locale);

        if (! $this->isRootNode()) {
            $label = array_reduce(array_reverse($this->getBreadCrumbs()), function ($carry, NestedNode $node) use ($locale, $withoutRoot) {
                if ($node->isRootNode()) {
                    return $withoutRoot ? $carry : $node->getLabel($locale) . ': ' . $carry;
                }

                return $node->getLabel($locale) . ' > ' . $carry;
            }, $this->getLabel($locale));
        }

        return $label;
    }

    protected function getLocalizedModelValue(string $attribute, string $locale, $default = null)
    {
        $model = $this->getModel();

        if (method_exists($model, 'isDynamic') && $model->isDynamic($attribute)) {
            return $model->dynamic($attribute, $locale, $default);
        }

        return $model->$attribute ?? $default;
    }

    // get all children
    // get direct children
    // get parent

    // adjust url accordingly

    // ADMIN:
        // Show structure on index
        // Allow sorting and moving
}
