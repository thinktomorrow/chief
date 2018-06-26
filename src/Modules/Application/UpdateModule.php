<?php

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Models\UniqueSlug;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class UpdateModule
{
    use TranslatableCommand;

    public function handle($id, string $slug, array $translations, array $files, array $files_order): Module
    {
        try {
            DB::beginTransaction();

            $module = Module::ignoreCollection()->findOrFail($id);
            $module->slug = $slug;
            $module->save();

            $this->saveModuleTranslations($module, $translations);

            app(UploadMedia::class)->fromUploadComponent($module, $files, $files_order);

            DB::commit();
            return $module->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function saveModuleTranslations(Module $module, $translations)
    {
        $this->saveTranslations($translations, $module, array_merge([
            'title'
        ], array_keys($module::translatableFields())));
    }
}
