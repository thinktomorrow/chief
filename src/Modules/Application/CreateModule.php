<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\ManagedModels\Presets\Fragment;
use Thinktomorrow\Chief\Shared\Concerns\Translatable\TranslatableCommand;

class CreateModule
{
    use TranslatableCommand;

    public function handle(string $registerKey, string $slug, $owner_type = null, $owner_id = null): Fragment
    {
        try {
            DB::beginTransaction();

            // Fetch managed model and create it via eloquent.
            $class = Relation::getMorphedModel($registerKey);
            $module = $class::create(['slug' => $slug, 'owner_type' => $owner_type, 'owner_id' => $owner_id]);

            DB::commit();

            return $module->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
