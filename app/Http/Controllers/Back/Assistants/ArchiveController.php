<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Urls\UrlRecord;

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

        $managers = $manager->assistant('archive')->findAll();

        return view('chief::back.managers.archive.index', [
            'modelManager' => $manager,
            'managers' => $managers,
        ]);
    }

    public function archive(Request $request, $key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        if($redirectReference = $request->get('redirect_id'))
        {
            $model = FlatReferenceFactory::fromString($redirectReference)->instance();

            $targetRecords = UrlRecord::getByModel($model);

            // Ok now get all urls from this model and point them to the new records
            foreach(UrlRecord::getByModel($manager->model()) as $urlRecord) {
                if($targetRecord = $targetRecords->first(function($record) use($urlRecord){
                    return ($record->locale == $urlRecord->locale && !$record->isRedirect());
                })){
                    $urlRecord->redirectTo($targetRecord);
                }
            }
        }

        $manager->assistant('archive')
                        ->guard('archive')
                        ->archive();

        return redirect()->to($manager->route('index'))->with('messages.success', $manager->details()->title .' is gearchiveerd.');
    }

    public function unarchive($key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        $manager->assistant('archive')
            ->guard('unarchive')
            ->unarchive();

        return redirect()->to($manager->route('index'))->with('messages.success', $manager->details()->title .' is hersteld.');
    }
}
