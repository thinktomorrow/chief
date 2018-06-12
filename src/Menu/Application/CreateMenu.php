<?php

namespace Thinktomorrow\Chief\Menu\Application;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Common\UniqueSlug;
use Thinktomorrow\Chief\App\Http\Requests\MenuCreateRequest;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\MenuItemTranslation;

class CreateMenu
{
    use TranslatableCommand;

    public function handle(MenuCreateRequest $request): MenuItem
    {
        try{
            DB::beginTransaction();

            $menu = MenuItem::create();

            if(($type = $request->get('type')) == 'custom'){
                $menu->url = $request->get('url');
            }elseif($type == 'internal'){
                $menu->page_id = $this->getPage($request->get('page_id'))->id;
            }

            $menu->type = $type;

            $translations = $request->get('trans');

            $this->saveTranslations($translations, $menu, [
                'label', 'url'
            ]);

            $menu->save();

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
