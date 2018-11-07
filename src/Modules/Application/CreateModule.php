<?php

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;

class CreateModule
{
    use TranslatableCommand;

    public function handle(string $morphKey, string $slug, $page_id = null): Module
    {
        try {
            DB::beginTransaction();

            $module = Module::create(['morph_key' => $morphKey, 'slug' => $slug, 'page_id' => $page_id]);

            DB::commit();

            return $module->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
