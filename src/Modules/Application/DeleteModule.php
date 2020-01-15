<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Relations\Relation;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;

class DeleteModule
{
    use TranslatableCommand;

    public function handle($id)
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
