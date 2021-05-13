<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Application;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Shared\Helpers\Form;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Menu\MenuItem;

class UpdateMenu
{
    public function handle($id, MenuRequest $request): MenuItem
    {
        try {
            DB::beginTransaction();

            $request = $this->forceRemoveUrlWhenNoLinkIsRequested($request);

            $model = MenuItem::find($id);
            $model->type = $request->input('type', null);
            $model->parent_id = ($request->input('allow_parent') && $request->input('parent_id')) ? $request->input('parent_id') : null;
            $model->order = $request->input('order', 0);

            if ($request->input('owner_reference')) {
                $owner = ModelReference::fromString($request->input('owner_reference'));
                $model->owner_type = $owner->className();
                $model->owner_id = $owner->id();
            }

            $this->reorderAgainstSiblings($model);

            Form::foreachTrans($request->input('trans', []), function ($locale, $key, $value) use ($model) {
                $model->setDynamic($key, $value, $locale);
            });

            $model->save();

            DB::commit();

            return $model->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function reorderAgainstSiblings(MenuItem $menu): void
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

    private function reorder(array $sequence): void
    {
        array_walk($sequence, function ($order, $id) {
            MenuItem::withoutGlobalScope(SoftDeletingScope::class)
                ->where('id', $id)
                ->update(['order' => $order]);
        });
    }

    /**
     * If no link is required, we make sure to remove any current url and force it in the request so we remove it
     *
     * @param MenuRequest $request
     * @return \Illuminate\Http\Request|MenuRequest
     */
    private function forceRemoveUrlWhenNoLinkIsRequested(MenuRequest $request)
    {
        if (MenuItem::TYPE_NOLINK == $request->input('type')) {
            $trans = $request->input('trans', []);

            foreach (array_keys($trans) as $locale) {
                $trans[$locale]['url'] = null;
            }

            $request = $request->merge(['trans' => $trans]);
        }

        return $request;
    }
}
