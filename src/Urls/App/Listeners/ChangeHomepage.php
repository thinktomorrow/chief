<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Listeners;

use Thinktomorrow\Chief\Admin\Settings\Setting;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Urls\App\Actions\ChangeHomepageUrl;
use Thinktomorrow\Chief\Urls\App\Actions\ReactivateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class ChangeHomepage
{
    private UrlApplication $application;

    private UrlRepository $repository;

    public function __construct(UrlApplication $application, UrlRepository $repository)
    {
        $this->application = $application;
        $this->repository = $repository;
    }

    public function onSettingChanged(array $existingValues): void
    {
        $setting = Setting::findByKey(Setting::HOMEPAGE);

        $modelReferences = is_array($setting->value)
            ? $setting->value
            : array_fill_keys(\Thinktomorrow\Chief\Sites\ChiefSites::locales(), $setting->value);

        $this->assertNoEmptyValues($modelReferences);

        foreach ($modelReferences as $locale => $modelReferenceString) {
            // If existing value has changed, we'll need to revert this previous value
            if (isset($existingValues[$locale])) {
                if ($modelReferenceString != $existingValues[$locale]) {
                    $modelReference = ModelReference::fromString(($existingValues[$locale]));

                    if ($recentRedirect = $this->repository->findRecentRedirectByModel($modelReference, $locale)) {
                        $this->application->reactivateUrl(new ReactivateUrl($recentRedirect->id));
                    }
                }
            }

            $modelReference = ModelReference::fromString($modelReferenceString);

            $this->application->changeHomepageUrl(new ChangeHomepageUrl($modelReference, $locale));
        }
    }

    public function onUrlChanged(UrlRecord $urlRecord): void
    {
        $model = Morphables::instance($urlRecord->model_type)->find($urlRecord->model_id);

        if (! $homepage = Setting::findByKey(Setting::HOMEPAGE)) {
            $homepage = Setting::create(['key' => Setting::HOMEPAGE, 'value' => []]);
        }

        $homepage->value = array_merge($homepage->value, [$urlRecord->locale => $model->modelReference()->getShort()]);
        $homepage->save();
    }

    private function assertNoEmptyValues(array $modelReferences): void
    {
        foreach ($modelReferences as $locale => $modelReferenceString) {
            if (! $modelReferenceString) {
                throw new \InvalidArgumentException('Homepage setting value cannot be empty. Value for locale '.$locale.' is missing.');
            }
        }
    }
}
