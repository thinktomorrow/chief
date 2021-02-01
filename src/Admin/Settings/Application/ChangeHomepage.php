<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Settings\Application;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Admin\Settings\Setting;
use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Site\Urls\Application\RevertUrlSlug;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ChangeHomepage
{
    public function onSettingChanged(array $existingValues)
    {
        $setting = Setting::findByKey(Setting::HOMEPAGE);

        $modelReferences = is_array($setting->value)
            ? $setting->value
            : array_fill_keys(config('chief.locales'), $setting->value);

        $this->assertNoEmptyValues($modelReferences);

        foreach ($modelReferences as $locale => $modelReferenceString) {

            // If existing value has changed, we'll need to revert this previous value
            if (isset($existingValues[$locale])) {
                if ($modelReferenceString != $existingValues[$locale]) {
                    $modelReferenceInstance = ModelReference::fromString(($existingValues[$locale]));
                    (new RevertUrlSlug())->handle($modelReferenceInstance->instance(), $locale);
                }
            }

            $modelReferenceInstance = ModelReference::fromString($modelReferenceString);
            (new SaveUrlSlugs())->handle($modelReferenceInstance->instance(), [$locale => '/'], false);
        }
    }

    public function onUrlChanged(UrlRecord $urlRecord)
    {
        $model = Morphables::instance($urlRecord->model_type)->find($urlRecord->model_id);

        if (!$homepage = Setting::findByKey(Setting::HOMEPAGE)) {
            $homepage = Setting::create(['key' => Setting::HOMEPAGE, 'value' => []]);
        }

        $homepage->value = array_merge($homepage->value, [$urlRecord->locale => $model->modelReference()->get()]);
        $homepage->save();
    }

    private function assertNoEmptyValues(array $modelReferences)
    {
        foreach ($modelReferences as $locale => $modelReferenceString) {
            if (!$modelReferenceString) {
                throw new \InvalidArgumentException('Homepage setting value cannot be empty. Value for locale ' . $locale . ' is missing.');
            }
        }
    }
}
