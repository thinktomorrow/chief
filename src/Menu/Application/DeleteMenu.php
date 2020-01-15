<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;

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
