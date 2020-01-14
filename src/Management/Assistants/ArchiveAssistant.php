<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\Management\Application\ArchiveManagedModel;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Application\UnarchiveManagedModel;
use Thinktomorrow\Chief\Urls\UrlRecord;

class ArchiveAssistant implements Assistant
{
    /** @var Manager */
    private $manager;

    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public static function key(): string
    {
        return 'archive';
    }

    public function manager(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function isArchived(): bool
    {
        return $this->manager->existingModel()->isArchived();
    }

    public function archivedAt(): Carbon
    {
        return new Carbon($this->manager->existingModel()->archived_at);
    }

    public function findAllArchived(): Collection
    {
        return $this->manager->modelInstance()::archived()->get()->map(function ($model) {
            return $this->managers->findByModel($model);
        });
    }

    public function route($verb): ?string
    {
        $routes = [
            'index' => route('chief.back.assistants.view', [$this->key(),'index', $this->manager->managerKey()]),
        ];

        if (array_key_exists($verb, $routes)) {
            return $routes[$verb] ?? null;
        }

        $modelRoutes = [
            'archive'   => route('chief.back.assistants.update', [$this->key(), 'archive', $this->manager->managerKey(), $this->manager->existingModel()->id]),
            'unarchive' => route('chief.back.assistants.update', [$this->key(), 'unarchive', $this->manager->managerKey(), $this->manager->existingModel()->id]),
        ];

        return isset($modelRoutes[$verb]) ? $modelRoutes[$verb] : null;
    }

    public function can($verb): bool
    {
        return !is_null($this->route($verb));
    }

    private function guard($verb): Assistant
    {
        if(!$this->manager->existingModel() instanceof StatefulContract){
            throw new \InvalidArgumentException('ArchiveAssistant requires the model to implement the StatefulContract. ['.get_class($this->model).'] given instead.');
        }

        if (! $this->can($verb)) {
            NotAllowedManagerRoute::notAllowedVerb($verb, $this->manager);
        }

        return $this;
    }

    public function index(Request $request)
    {
        return view('chief::back.managers.archive.index', [
            'modelManager' => $this->manager,
            'managers' => $this->findAllArchived(),
        ]);
    }

    public function archive(Request $request)
    {
        $this->guard('archive');

        // If a redirect_id is passed along the request, it indicates the admin wants this page to be redirected to another one.
        if ($redirectReference = $request->get('redirect_id')) {
            $model = FlatReferenceFactory::fromString($redirectReference)->instance();

            $targetRecords = UrlRecord::getByModel($model);

            // Ok now get all urls from this model and point them to the new records
            foreach (UrlRecord::getByModel($this->manager->existingModel()) as $urlRecord) {
                if ($targetRecord = $targetRecords->first(function ($record) use ($urlRecord) {
                    return ($record->locale == $urlRecord->locale && !$record->isRedirect());
                })) {
                    $urlRecord->redirectTo($targetRecord);
                }
            }
        }

        app(ArchiveManagedModel::class)->handle($this->manager->existingModel());

        return redirect()->to($this->manager->route('index'))->with('messages.success', $this->manager->details()->title .' is gearchiveerd.');
    }

    public function unarchive()
    {
        $this->guard('unarchive');

        app(UnArchiveManagedModel::class)->handle($this->manager->existingModel());

        return redirect()->to($this->manager->route('index'))->with('messages.success', $this->manager->details()->title .' is hersteld.');
    }
}
