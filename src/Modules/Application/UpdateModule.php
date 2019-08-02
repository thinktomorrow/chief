<?php

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Models\UniqueSlug;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;

class UpdateModule
{
    use TranslatableCommand;

    public function handle($id, string $internal_title, array $translations, array $files, array $files_order): Module
    {
        try {
            DB::beginTransaction();

            $module = Module::findOrFail($id);
            $module->internal_title = $internal_title;
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
        // TODO: this should come from the manager->fields() as fieldgroup
        $translatableColumns = [];
        foreach ($module::translatableFields() as $translatableField) {
            $translatableColumns[] = $translatableField->column();
        }

        $this->saveTranslations($translations, $module, array_merge([
            'title'
        ], $translatableColumns));
    }
}
