<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Management\Application\StoreManager;
use Thinktomorrow\Chief\Management\Exceptions\DeleteAborted;
use Thinktomorrow\Chief\Management\Application\DeleteManager;
use Thinktomorrow\Chief\Management\Application\UpdateManager;

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

        $manager->guard('index');

        $managers = $manager->findAllManaged();

        return view('chief::back.managers.index', [
            'modelManager' => $manager,
            'managers' => $managers,
        ]);
    }

    public function create(string $key)
    {
        $manager = $this->managers->findByKey($key);

        $manager->guard('create');

        return view('chief::back.managers.create', [
            'manager' => $manager,
        ]);
    }

    public function store(string $key, Request $request)
    {
        $modelManager = $this->managers->findByKey($key);

        $manager = app(StoreManager::class)->handle($modelManager, $request);

        return redirect()->to($manager->route('edit'))
                         ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->modelDetails()->title . '" is toegevoegd');
    }

    public function edit(string $key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        $manager->guard('edit');
        return view('chief::back.managers.edit', [
            'manager' => $manager,
        ]);
    }

    public function update(string $key, $id, Request $request)
    {
        $manager = $this->managers->findByKey($key, $id);

        app(UpdateManager::class)->handle($manager, $request);

        return redirect()->to($manager->route('edit'))
                         ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->modelDetails()->title . '" werd aangepast');
    }

    public function delete(string $key, $id, Request $request)
    {
        $manager = $this->managers->findByKey($key, $id);

        try {
            app(DeleteManager::class)->handle($manager, $request);
        } catch (DeleteAborted $e) {
            return redirect()->back()->with('messages.warning', $manager->modelDetails()->singular . ' is niet verwijderd.');
        }

        return redirect()->to($manager->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->modelDetails()->title . '" is verwijderd.');
    }
}
