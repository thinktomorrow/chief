<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Application;


class UpdateModule
{
    public function handle($id, string $slug, array $translations, array $files, array $files_order): Module
    {
        $module = Module::findOrFail($id);
        $module->slug = $slug;
        $module->save();
dump($module->id, $module->slug, $module);
        $module->saveFields($module->fields(), array_merge($translations, ['filesOrder' => $files_order]), $files);
//
//
//        try {
//            DB::beginTransaction();
//
//            $module = Module::findOrFail($id);
//            $module->slug = $slug;
//            $module->save();
//
//            $module->saveFields($module->fields(), array_merge($translations, ['filesOrder' => $files_order]), $files);
//
//            DB::commit();
//
//            return $module->fresh();
//        } catch (\Throwable $e) {
//            DB::rollBack();
//            throw $e;
//        }
    }
}
