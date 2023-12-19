<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicatePage;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait DuplicateAssistant
{
    abstract protected function guard(string $action, $model = null);
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesDuplicateAssistant(): array
    {
        return [
            ManagedRoute::post('duplicate', 'duplicate/{id}'),
            ManagedRoute::post('duplicate-on-create', 'duplicate'),
        ];
    }

    public function canDuplicateAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['duplicate', 'duplicate-on-create'])) {
            return false;
        }

        try {
            $this->authorize('create-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        return true;
    }

    public function duplicate(Request $request, $id)
    {
        if(!$model = $this->managedModelClass()::findOrFail($id)) {
            throw new \InvalidArgumentException('Missing model id or model not found by [' . $id. '].');
        }

        $this->guard('duplicate', $model);

        // $model = Duplicate ...
        $copiedModel = app(DuplicatePage::class)->handle($model, $this->resource->getTitleAttributeKey());

        Audit::activity()->performedOn($model)->log('duplicated');

        return redirect()->to($this->route('edit', $copiedModel))->with('messages.success', $this->resource->getPageTitle($model) . ' is gekopieerd.');
    }

    public function duplicateOnCreate(Request $request)
    {
        $modelId = $request->input('model_id');

        if(!$modelId) {
            throw new \InvalidArgumentException('Missing model id');
        }

        return $this->duplicate($request, $modelId);
    }
}
