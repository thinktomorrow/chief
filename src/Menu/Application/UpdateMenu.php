<?php

namespace Thinktomorrow\Chief\Menu\Application;

use Thinktomorrow\Chief\Common\Relations\RelatedCollection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Models\UniqueSlug;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\App\Http\Requests\MenuUpdateRequest;

class UpdateMenu
{
    use TranslatableCommand;

    public function handle($id, MenuUpdateRequest $request): MenuItem
    {
        try{
            DB::beginTransaction();

            $menu = MenuItem::find($id);
            if($menu->type == 'custom'){
                $menu->url = $request->get('url');
            }elseif($menu->type == 'internal'){
                $menu->page_id = $this->getPage($request->get('page_id'))->id;
            }

            $menu->save();

            $this->saveTranslations($request->get('trans'), $menu, [
                'label'
            ]);

            DB::commit();

            return $menu->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getPage($page_id)
    {
        return Page::inflate($page_id);
    }
}
