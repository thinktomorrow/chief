<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\DuplicateContext;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class DuplicatePage
{
    private DuplicateModel $duplicateModel;

    private ContextRepository $contextRepository;

    private ContextApplication $contextApplication;

    public function __construct(ContextRepository $contextRepository, DuplicateModel $duplicateModel, ContextApplication $contextApplication)
    {
        $this->duplicateModel = $duplicateModel;
        $this->contextRepository = $contextRepository;
        $this->contextApplication = $contextApplication;
    }

    public function handle(Model&ReferableModel $model, string $titleKey = 'title'): Model
    {
        $copiedModel = $this->duplicateModel->handle($model, $titleKey);

        if ($copiedModel instanceof StatefulContract && in_array(PageState::draft, $copiedModel->getStateConfig(PageState::KEY)->getStates())) {
            $copiedModel->changeState(PageState::KEY, PageState::draft);
            $copiedModel->save();
        }

        foreach ($this->contextRepository->getByOwner($model->modelReference()) as $context) {
            $this->contextApplication->duplicate(new DuplicateContext($context->id, $copiedModel));
        }

        return $copiedModel;
    }
}
