<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\ModuleCreateRequest;
use Thinktomorrow\Chief\Modules\Application\UpdateModule;
use Thinktomorrow\Chief\App\Http\Requests\ModuleUpdateRequest;
use Thinktomorrow\Chief\Modules\Application\DeleteModule;

class ModulesController extends Controller
{
    public function index()
    {
        $this->authorize('view-page');

        return view('chief::back.modules.index', [
            'modules' => Module::withoutPageSpecific()->get(),
            'collections' => Module::availableCollections()->values()->map->toArray()->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(ModuleCreateRequest $request)
    {
        $this->authorize('create-page');

        $module = app(CreateModule::class)->handle(
            $request->get('collection'),
            $request->get('slug'),
            $request->get('page_id')
        );

        return redirect()->route('chief.back.modules.edit', $module->getKey())->with('messages.success', $module->slug. ' is toegevoegd. Happy editing!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->authorize('update-page');

        $module = Module::findOrFail($id);
        $module->injectTranslationForForm();

        return view('chief::back.modules.edit', [
            'module'            => $module,
            'images'          => $this->populateMedia($module),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function update(ModuleUpdateRequest $request, $id)
    {
        $this->authorize('update-page');

        $module = app(UpdateModule::class)->handle(
            $id,
            $request->slug,
            $request->trans,
            array_merge($request->get('files', []), $request->file('files', [])), // Images are passed as base64 strings, not as file, Documents are passed via the file segment
            $request->get('filesOrder', [])
        );

        // Page specific if redirect to the page
        if ($module->isPageSpecific()) {
            $route = route('chief.back.pages.edit', $module->page_id);
            return redirect()->to($route.'#modules')->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i> '.$module->slug.' werd aangepast');
        }

        return redirect()->route('chief.back.modules.index')->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i> '.$module->slug.' werd aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->authorize('delete-page');

        app(DeleteModule::class)->handle($id);

        return redirect()->route('chief.back.modules.index')->with('messages.warning', 'De module werd verwijderd.');
    }

    /**
     * @param $module
     * @return array
     */
    private function populateMedia($module): array
    {
        $images = array_fill_keys($module->mediaFields('type'), []);

        foreach ($module->getAllFiles()->groupBy('pivot.type') as $type => $assetsByType) {
            foreach ($assetsByType as $asset) {
                $images[$type][] = (object) [
                    'id'  => $asset->id, 'filename' => $asset->getFilename(),
                    'url' => $asset->getFileUrl()
                ];
            }
        }

        return $images;
    }
}
