<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;

class CreateMenu
{
    use TranslatableCommand;

    public function handle(MenuRequest $request): MenuItem
    {
        try {
            DB::beginTransaction();
            $menu = MenuItem::create();
            $menu->type = $request->input('type', null);
            $menu->parent_id = ($request->input('allow_parent') && $request->input('parent_id')) ? $request->input('parent_id') : null;
            $menu->page_id = ($page_id = $request->input('page_id')) ? $this->getPage($request->input('page_id'))->id : null;
            $menu->menu_type = $request->input('menu_type', 'main');
            $menu->save();

            $this->saveTranslations($request->input('trans'), $menu, [
                'label', 'url'
            ]);

            DB::commit();

            return $menu->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getPage($flat_reference)
    {
        return FlatReferenceCollection::fromFlatReferences([$flat_reference])->first();
    }
}
