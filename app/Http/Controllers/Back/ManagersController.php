<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Application\StoreManager;
use Thinktomorrow\Chief\Management\Application\UpdateManager;
use Thinktomorrow\Chief\Management\Managers;

class ManagersController extends Controller
{
    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function index(string $key)
    {
        $manager = $this->managers->findByKey($key);

        $managers = $manager::findAllManaged();

        return view('chief::back.managers.index', [
            'modelManager' => $manager,
            'managers' => $managers,
        ]);
    }

    public function create(string $key)
    {
        $manager = $this->managers->findByKey($key);

        // Prep the fields, arrange in proper order
        $fields = $manager->fields();

        return view('chief::back.managers.create', [
            'manager' => $manager,
            'fields' => $fields,
        ]);
    }

    public function store(string $key, Request $request)
    {
        $modelManager = $this->managers->findByKey($key);

        $manager = app(Storemanager::class)->handle($modelManager, $request);

        return redirect()->to($manager->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->managedModelDetails()->title . '" werd aangepast');
    }

    public function edit(string $key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        // Prep the fields, arrange in proper order
        $fields = $manager->fields();

        return view('chief::back.managers.edit', [
            'manager' => $manager,
            'fields' => $fields,
        ]);
    }

    public function update(string $key, $id, Request $request)
    {
        $manager = $this->managers->findByKey($key, $id);

        app(Updatemanager::class)->handle($manager, $request);

        return redirect()->to($manager->route('edit'))
                         ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->managedModelDetails()->title . '" werd aangepast');
    }

    public function delete(string $key, $id)
    {
        //
    }
}
