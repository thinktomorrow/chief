<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\App\Actions\DuplicateContext;
use Thinktomorrow\Chief\Fragments\Repositories\ContextRepository;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class DuplicatePage
{
    private DuplicateContext $duplicateContext;
    private DuplicateModel $duplicateModel;
    private ContextRepository $contextRepository;

    public function __construct(ContextRepository $contextRepository, DuplicateModel $duplicateModel, DuplicateContext $duplicateContext)
    {
        $this->duplicateContext = $duplicateContext;
        $this->duplicateModel = $duplicateModel;
        $this->contextRepository = $contextRepository;
    }

    public function handle(Model & ReferableModel $model, string $titleKey = 'title'): Model
    {
        $copiedModel = $this->duplicateModel->handle($model, $titleKey);

        if ($copiedModel instanceof StatefulContract && in_array(PageState::draft, $copiedModel->getStateConfig(PageState::KEY)->getStates())) {
            $copiedModel->changeState(PageState::KEY, PageState::draft);
            $copiedModel->save();
        }

        foreach ($this->contextRepository->getByOwner($model) as $context) {
            $this->duplicateContext->handle($context->id, $copiedModel, $context->locale);
        }

        return $copiedModel;
    }
}
