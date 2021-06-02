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
        ];
    }

    public function canDuplicateAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['duplicate'])) {
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
        $model = $this->managedModelClass()::findOrFail($id);

        $this->guard('duplicate', $model);

        // $model = Duplicate ...
        $copiedModel = app(DuplicatePage::class)->handle($model);

        Audit::activity()->performedOn($model)->log('duplicated');

        return redirect()->to($this->route('edit', $copiedModel))->with('messages.success', $model->adminConfig()->getPageTitle() . ' is gekopieerd.');
    }
}
