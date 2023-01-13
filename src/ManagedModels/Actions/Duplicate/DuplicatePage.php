<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class DuplicatePage
{
    private DuplicateContext $duplicateContext;
    private DuplicateModel $duplicateModel;

    public function __construct(DuplicateModel $duplicateModel, DuplicateContext $duplicateContext)
    {
        $this->duplicateContext = $duplicateContext;
        $this->duplicateModel = $duplicateModel;
    }

    public function handle(Model $model, string $titleKey = 'title'): Model
    {
        $copiedModel = $this->duplicateModel->handle($model, $titleKey);

        if ($copiedModel instanceof StatefulContract && in_array(PageState::draft, $copiedModel->getStateConfig(PageState::KEY)->getStates())) {
            $copiedModel->changeState(PageState::KEY, PageState::draft);
            $copiedModel->save();
        }

        $this->duplicateContext->handle($model, $copiedModel);

        return $copiedModel;
    }
}
