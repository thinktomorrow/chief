<?php

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;

class CreateModule
{
    use TranslatableCommand;

    public function handle(string $registerKey, string $internal_title, $page_id = null): Module
    {
        try {
            DB::beginTransaction();

            // Fetch managed model and create it via eloquent.
            $model = app(Managers::class)->findByKey($registerKey)->model();

            $module = $model->create(['internal_title' => $internal_title, 'page_id' => $page_id]);

            DB::commit();

            return $module->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
