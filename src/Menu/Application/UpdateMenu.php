<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Application;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;

class UpdateMenu
{
    use TranslatableCommand;

    public function handle($id, MenuRequest $request): MenuItem
    {
        try {
            DB::beginTransaction();

            $request = $this->forceRemoveUrlWhenNoLinkIsRequested($request);

            $menu = MenuItem::find($id);
            $menu->type = $request->get('type', null);
            $menu->parent_id = ($request->get('allow_parent') && $request->get('parent_id')) ? $request->get('parent_id') : null;
            $menu->page_id = ($page_id = $request->get('page_id')) ? $this->getPage($request->get('page_id'))->id : null;
            $menu->order = $request->get('order', 0);

            $this->reorderAgainstSiblings($menu);
            $menu->save();

            $this->saveTranslations($request->get('trans'), $menu, [
                'label',
                'url',
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

    /**
     * If no link is required, we make sure to remove any current url and force it in the request so we remove it
     *
     * @param MenuRequest $request
     * @return \Illuminate\Http\Request|MenuRequest
     */
    private function forceRemoveUrlWhenNoLinkIsRequested(MenuRequest $request)
    {
        if (MenuItem::TYPE_NOLINK == $request->get('type')) {
            $trans = $request->get('trans', []);

            foreach ($trans as $locale => $translations) {
                $trans[$locale]['url'] = null;
            }

            $request = $request->merge(['trans' => $trans]);
        }

        return $request;
    }
}
