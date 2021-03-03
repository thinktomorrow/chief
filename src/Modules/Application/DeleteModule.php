<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\PageBuilder\Relations\Relation;
use Thinktomorrow\Chief\Shared\Concerns\Translatable\TranslatableCommand;

class DeleteModule
{
    use TranslatableCommand;

    public function handle($id): void
    {
        try {
            DB::beginTransaction();

            $module = Module::findOrFail($id);

            Relation::deleteRelationsOf($module->getMorphClass(), $module->id);

            // Mark the slug as deleted to avoid any conflict with newly created modules with the same slug.
            $module->update([
                'slug' => $module->slug . $this->appendDeleteMarker(),
            ]);

            $module->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function appendDeleteMarker(): string
    {
        return '_DELETED_' . time();
    }
}
