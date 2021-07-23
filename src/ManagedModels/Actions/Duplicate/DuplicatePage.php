<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;

class DuplicatePage
{
    private DuplicateContext $duplicateContext;
    private DuplicateModel $duplicateModel;

    public function __construct(DuplicateModel $duplicateModel, DuplicateContext $duplicateContext)
    {
        $this->duplicateContext = $duplicateContext;
        $this->duplicateModel = $duplicateModel;
    }

    public function handle(Model $model): Model
    {
        $copiedModel = $this->duplicateModel->handle($model);

        // TODO: check for HasPageState contract
        if ($copiedModel instanceof WithPageState) {
            $copiedModel->setPageState(PageState::DRAFT);
            $copiedModel->save();
        }

        $this->duplicateContext->handle($model, $copiedModel);

        return $copiedModel;
    }
}
