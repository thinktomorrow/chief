<?php

namespace Thinktomorrow\Chief\Fragments;

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

        if($fragment->hasModelId()){
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

//    public function saveFragments(string $key, array $fragments): void
//    {
//        // TEST
//        $id = 6;
//        $fragments = array_map(function (Fragment $fragment) use (&$id) {
//            return $fragment->modelId($id++);
//        }, $fragments);
//        //END TEST
//
//        // Reset all fragments in db that are no longer present
//        $this->removeDeadFragments($key, $fragments);
//
//        // order is set by order of array
//        $i = 0;
//
//        array_map(function (Fragment $fragment) use (&$i) {
//
//            // Fragment already exists
//            if ($fragment->hasModelId())
//            {
//                FragmentModel::find($fragment->getModelId())->update(array_merge([
//                    'key'   => $fragment->getKey(),
//                    'order' => $i++,
//                ], $fragment->getValues()));
//            } else
//            {
//                $this->fragments()->create(array_merge([
//                    'key'   => $fragment->getKey(),
//                    'order' => $i++,
//                ], $fragment->getValues()));
//            }
//
//        }, $fragments);
//    }

    protected function fragments()
    {
        return $this->morphMany(FragmentModel::class, 'owner');
    }
}
