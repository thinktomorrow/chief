<?php

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class CreateModule
{
    use TranslatableCommand;

    public function handle(string $collection, string $slug, array $translations): Module
    {
        try {
            DB::beginTransaction();

            $page = Module::create(['collection' => $collection, 'slug' => $slug]);

            foreach ($translations as $locale => $value) {
                $page->updateTranslation($locale, $value);
            }

            DB::commit();

            return $page->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
