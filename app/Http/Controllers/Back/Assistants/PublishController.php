<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class PublishController extends Controller
{
    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function publish(Request $request, $key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        $manager->assistant('publish')->publish();

        return redirect()->back()->with('messages.success', $manager->details()->title . ' is gepubliceerd. <a href="' . $manager->assistant('publish')->previewUrl() . '" target="_blank">Bekijk de pagina online</a>.');
    }

    public function unpublish(Request $request, $key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        $manager->assistant('publish')->unpublish();

        return redirect()->back()->with('messages.success', $manager->details()->title . ' is terug offline gehaald.');
    }
}
