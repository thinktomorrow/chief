<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class ArchiveController extends Controller
{
    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function index(Request $request, string $key)
    {
        $manager = $this->managers->findByKey($key);

        $manager->guard('index');

        $managers = $manager->assistant('archive')->findAll();

        return view('chief::back.managers.archive.index', [
            'modelManager' => $manager,
            'managers' => $managers,
        ]);
    }

    public function archive($key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        $manager->assistant('archive')->archive();

        return redirect()->back()->with('messages.success', $manager->details()->title .' is gearchiveerd.');
    }

    public function unarchive($key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        $manager->assistant('archive')->unarchive();

        return redirect()->to($manager->route('index'))->with('messages.success', $manager->details()->title .' is hersteld.');
    }
}
