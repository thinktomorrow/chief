<?php

namespace Thinktomorrow\Chief\Menu\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Common\Collections\CollectionKeys;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Models\UniqueSlug;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class UpdateMenu
{
    use TranslatableCommand;

    public function handle($id, MenuRequest $request): MenuItem
    {
        try {
            DB::beginTransaction();

            $menu = MenuItem::find($id);
            $menu->type = $request->get('type', null);
            $menu->parent_id = ($request->get('allow_parent') && $request->get('parent_id')) ? $request->get('parent_id') : null;
            $menu->page_id = ($page_id = $request->get('page_id')) ? $this->getPage($request->get('page_id'))->id : null;
            $menu->collection_type = $request->get('collection_type', null);
            $menu->order        = $request->get('order', 0);

            $this->reorderAgainstSiblings($menu);
            $menu->save();

            $this->saveTranslations($request->get('trans'), $menu, [
                'label', 'url'
            ]);

            DB::commit();

            return $menu->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function reorderAgainstSiblings(MenuItem $menu)
    {
        $siblings = MenuItem::where('parent_id', $menu->parent_id)->whereNotIn('id', [$menu->id])->get();

        $sequence = $siblings->pluck('order', 'id')->toArray();
        asort($sequence);

        if (in_array($menu->order, $sequence)) {
            foreach ($sequence as $id => $order) {
                if ($order < $menu->order) {
                } else {
                    $sequence[$id]++;
                }
            }
        }

        $sequence = $sequence + [$menu->id => $menu->order];

        asort($sequence);

        $this->reorder($sequence);
    }

    private function reorder(array $sequence)
    {
        array_walk($sequence, function ($order, $id) {
            MenuItem::withoutGlobalScope(SoftDeletingScope::class)
                ->where('id', $id)
                ->update(['order' => $order]);
        });
    }


    private function getPage($collection_id)
    {
        return FlatReferenceCollection::fromFlatReferences([$collection_id])->first();
    }
}
