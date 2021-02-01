<?php

namespace Thinktomorrow\Chief\Migrate\Legacy\Fragments;

use Illuminate\Support\Collection;

trait HasFragments
{
    public function getFragments(string $key): Collection
    {
        return $this->fragments()->where('key', $key)->orderBy('order', 'ASC')->get();
    }

    /**
     * Save a single fragment and return the record id
     *
     * @param Fragment $fragment
     * @return int
     */
    public function saveFragment(Fragment $fragment, int $order): int
    {
        $values = array_merge([
            'key' => $fragment->getKey(), 'order' => $order,
        ], $fragment->getValues());

        if ($fragment->hasModelId()) {
            $model =FragmentModel::find($fragment->getModelId());
            $model->update($values);

            return $model->id;
        }

        return $this->fragments()->create($values)->id;
    }

    public function removeAllFragments(string $key, array $excludedFragmentIds): void
    {
        $this->fragments()->where('key', $key)->whereNotIn('id', $excludedFragmentIds)->delete();
    }

    public function fragments()
    {
        return $this->morphMany(FragmentModel::class, 'owner');
    }
}
