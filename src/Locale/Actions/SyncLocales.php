<?php

namespace Thinktomorrow\Chief\Locale\Actions;

use Thinktomorrow\Chief\Locale\Events\LocalesUpdated;
use Thinktomorrow\Chief\Locale\LocaleRepository;
use Thinktomorrow\Chief\Locale\Localisable;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class SyncLocales
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function handle(ReferableModel & Localisable $model, array $locales): void
    {
        /** @var LocaleRepository $repository */
        $repository = $this->registry->findResourceByModel($model::class);

        $previousState = $model->getLocales();

        $repository->saveLocales($model, $locales);

        event(new LocalesUpdated($model->modelReference(), $locales, $previousState));
    }
}
