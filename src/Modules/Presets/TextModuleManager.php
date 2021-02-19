<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Presets;

use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Manager;

class TextModuleManager implements Manager
{
    use ManagerDefaults;
    use CrudAssistant;

//    public function details(): Details
//    {
//        $modelDetails = parent::details();
//        $modelDetails = $modelDetails->set('plural', $this->model->isPageSpecific() ? 'eigen modules' : 'vaste modules');
//        $modelDetails = $modelDetails->set('title', $this->model->slug);
//
//        return $modelDetails;
//    }
//
//    public function route($verb): ?string
//    {
//
//        /**
//         * Page specific modules are expected to be found and managed in the context of a certain page.
//         * Therefore the index of these modules is at the modules tab of this page model.
//         */
//        if ($verb == 'index' && $this->model->isPageSpecific()) {
//            if (!$this->model->page) {
//                throw new \RuntimeException('Cannot retrieve parent for page specific module [type: ' . $this->registration->key() . ', id: ' . $this->existingModel()->id . ']');
//            }
//
//            return app(Managers::class)->findByModel($this->model->page)->route('edit') . '#eigen-modules';
//        }
//
//        $routes = [
//            'index'  => route('chief.back.modules.index', [$this->registration->key()]),
//            'create' => route('chief.back.managers.create', [$this->registration->key()]),
//            'store'  => route('chief.back.managers.store', [$this->registration->key()]),
//        ];
//
//        if (array_key_exists($verb, $routes)) {
//            return $routes[$verb] ?? null;
//        }
//
//        $routes = array_merge($routes, [
//            'edit'   => route('chief.back.managers.edit', [$this->registration->key(), $this->existingModel()->id]),
//            'update' => route('chief.back.managers.update', [$this->registration->key(), $this->existingModel()->id]),
//            'delete' => route('chief.back.managers.delete', [$this->registration->key(), $this->existingModel()->id]),
//            'upload' => route('chief.back.managers.media.upload', [
//                $this->registration->key(),
//                $this->existingModel()->id,
//            ]),
//        ]);
//
//        return $routes[$verb] ?? null;
//    }
//
//    /**
//     * The set of fields that should be manageable for a certain model.
//     *
//     * Additionally, you should:
//     * 1. Make sure to setup the proper migrations and
//     * 2. For a translatable field you should add this field to the $translatedAttributes property of the model as well.
//     *
//     * @return Fields
//     */

//
//    public function saveCreateFields(Request $request): void
//    {
//        // Store the morph_key upon creation
//        if ($this->model instanceof MorphableContract && !$this->model->morph_key) {
//            $this->model->morph_key = $this->model->morphKey();
//        }
//
//        parent::saveCreateFields($request);
//    }
//
//    public function delete()
//    {
//        $this->guard('delete');
//
//        if (request()->get('deleteconfirmation') !== 'DELETE') {
//            throw new DeleteAborted();
//        }
//
//        app(DeleteModule::class)->handle($this->model->id);
//    }
//
//    public function storeRequest(Request $request): Request
//    {
//        $trans = [];
//        foreach ($request->input('trans', []) as $locale => $translation) {
//            if (is_array_empty($translation)) {
//                continue;
//            }
//
//            $trans[$locale] = $translation;
//        }
//
//        return $request->merge(['trans' => $trans]);
//    }
//
//    public function updateRequest(Request $request): Request
//    {
//        $trans = [];
//        foreach ($request->input('trans', []) as $locale => $translation) {
//            if (is_array_empty($translation)) {
//
//                // Nullify all values
//                $trans[$locale] = array_map(function ($value) {
//                    return null;
//                }, $translation);
//                continue;
//            }
//
//            $trans[$locale] = $translation;
//        }
//
//        return $request->merge(['trans' => $trans]);
//    }
    public function managedModelClass(): string
    {
        return TextModule::class;
    }
}
