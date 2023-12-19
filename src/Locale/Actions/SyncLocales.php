<?php

namespace Thinktomorrow\Chief\Locale\Actions;

use Thinktomorrow\Chief\Locale\ChiefLocaleConfig;
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

    public function handle(string $resourceKey, ReferableModel & Localisable $model, array $locales): void
    {
        /** @var LocaleRepository $repository */
        $repository = $this->registry->resource($resourceKey);

        $previousState = $model->getLocales();

        $locales = $this->sortLocales($locales);

        $repository->saveLocales($model, $locales);

        event(new LocalesUpdated($model->modelReference(), $locales, $previousState));

        // TODO: create new context is locale is added
        // Option to duplicate existing context??? -> with same fragments (fragment with these two locales, not 2 separate fragments)
        // TODO: create contexts when page is created (witht hte default locales)
    }

    private function sortLocales(array $locales): array
    {
        $indices = array_flip(ChiefLocaleConfig::getLocales());

        $result = [];

        foreach ($locales as $locale) {
            $result[$indices[$locale]] = $locale;
        }

        ksort($result);

        return array_values($result);
    }
}
