<?php
namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class DeleteModule
{
    use TranslatableCommand;

    public function handle($id)
    {
        try {
            DB::beginTransaction();

            $module = Module::findOrFail($id);
            $module->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
