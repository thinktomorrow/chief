<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\DuplicateContext;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
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
        $stateKeys = $model instanceof StatefulContract ? $model->getStateKeys() : [];
        $copiedModel = $this->duplicateModel->handle($model, $titleKey, $stateKeys);

        if ($stateKeys !== []) {
            $copiedModel->refresh();
        }

        foreach ($this->contextRepository->getByOwner($model->modelReference()) as $context) {
            $this->contextApplication->duplicate(new DuplicateContext($context->id, $copiedModel));
        }

        return $copiedModel;
    }
}
