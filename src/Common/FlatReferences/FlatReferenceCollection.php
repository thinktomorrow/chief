<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\FlatReferences;

use Illuminate\Support\Collection;

class FlatReferenceCollection extends Collection
{
    /**
     * Inflate collection id strings to their respective models
     *
     * @param iterable $referenceStrings
     * @return Collection
     */
    public static function fromFlatReferences(iterable $referenceStrings): Collection
    {
        // Empty array passage from form submission happens as array with one empty item
        if (count($referenceStrings) == 1 && is_null(reset($referenceStrings))) {
            $referenceStrings = [];
        }

        return (new Collection($referenceStrings))->reject(function ($referenceString) {
            return is_null($referenceString);
        })->map(function ($referenceString) {
            return FlatReferenceFactory::fromString($referenceString)->instance();
        });
    }

    public function toFlatReferences(): Collection
    {
        return (new Collection($this->all()))->map(function (ProvidesFlatReference $item) {
            return $item->flatReference()->get();
        });
    }
}
