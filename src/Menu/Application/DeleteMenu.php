<?php

namespace Thinktomorrow\Chief\Menu\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class DeleteMenu
{
    use TranslatableCommand;

    public function handle($id): MenuItem
    {
        try {
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
}
