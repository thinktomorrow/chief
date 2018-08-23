<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\PageSets;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Common\Relations\ActingAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsChild;

/**
 * Class AppliedPageSet
 *
 * @property $id
 * @property $action
 * @property $key
 * @property $parameters
 */
class StoredPageSetReference extends Model implements ActsAsChild
{
    use ActingAsChild;

    public $table = 'pagesets';
    public $guarded = [];
    public $timestamps = false;
    public $casts = [
        'parameters' => 'array',
    ];

    /**
     * Run the query and collect the resulting pages into a PageSet object.
     *
     * @return PageSet
     */
    public function toPageSet()
    {
        return PageSet::fromReference($this->toReference());
    }

    public function toReference()
    {
        return new PageSetReference($this->key, $this->action, $this->parameters);
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(static::class, $this->id);
    }

    public function flatReferenceLabel(): string
    {
        return $this->key;
    }

    public function flatReferenceGroup(): string
    {
        return 'pageset';
    }
}