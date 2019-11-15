<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\App\Http\Requests\ModuleCreateRequest;

class ModulesController extends Controller
{
    public function index()
    {
        $this->authorize('view-page');
        $modules = Module::withoutPageSpecific()->orderBy('morph_key')->get()->groupBy('morph_key');

        return view('chief::back.modules.index', [
            'modules' => $modules,
        ]);
    }

    public function store(ModuleCreateRequest $request)
    {
        $manager = app(Managers::class)->findByKey($request->module_key);

        $manager->guard('store');

        $module = app(CreateModule::class)->handle(
            $request->get('module_key'),
            $request->get('slug'),
            $request->get('page_id')
        );

        // Populate the manager with the model so we can direct the admin to the correct page.
        $manager->manage($module);

        return redirect()->to($manager->route('edit'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->details()->title . '" is toegevoegd');
    }
}
