<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Site\Menu\Events\MenuItemDeleted;
use Thinktomorrow\Chief\Shared\Concerns\Translatable\TranslatableCommand;
use Thinktomorrow\Chief\Site\Menu\MenuItem;

class DeleteMenuItem
{
    use TranslatableCommand;

    public function handle($id): MenuItem
    {
        try {
            DB::beginTransaction();

            $model = MenuItem::find($id);

            $model->delete();

            DB::commit();

            event(new MenuItemDeleted((string)$model->id));

            return $model;
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
