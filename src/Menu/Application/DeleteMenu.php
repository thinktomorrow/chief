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

class DeleteMenu
{
    use TranslatableCommand;

    public function handle($id): MenuItem
    {
        try{
            DB::beginTransaction();

            $menuitem = MenuItem::find($id);

            $menuitem->delete();

            DB::commit();

            return $menuitem;
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
