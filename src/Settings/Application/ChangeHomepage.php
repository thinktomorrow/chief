<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Settings\Application;

use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Urls\Application\RevertUrlSlug;
use Thinktomorrow\Chief\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Urls\UrlRecord;

class ChangeHomepage
{
    public function onSettingChanged(array $existingValues)
    {
        $setting = Setting::findByKey(Setting::HOMEPAGE);

        $flatReferences = is_array($setting->value)
            ? $setting->value
            : array_fill_keys(config('translatable.locales'), $setting->value);

        $this->assertNoEmptyValues($flatReferences);

        foreach ($flatReferences as $locale => $flatReferenceString) {

            // If existing value has changed, we'll need to revert this previous value
            if (isset($existingValues[$locale])) {
                if ($flatReferenceString != $existingValues[$locale]) {
                    $flatReferenceInstance = FlatReferenceFactory::fromString(($existingValues[$locale]));
                    (new RevertUrlSlug($flatReferenceInstance->instance()))->handle($locale);
                }
            }

            $flatReferenceInstance = FlatReferenceFactory::fromString($flatReferenceString);
            (new SaveUrlSlugs($flatReferenceInstance->instance()))->strict(false)->handle([$locale => '/']);
        }
    }

    public function onUrlChanged(UrlRecord $urlRecord)
    {
        $model = Morphables::instance($urlRecord->model_type)->find($urlRecord->model_id);

        if (!$homepage = Setting::findByKey(Setting::HOMEPAGE)) {
            $homepage = Setting::create(['key' => Setting::HOMEPAGE, 'value' => []]);
        }

        $homepage->value = array_merge($homepage->value, [$urlRecord->locale => $model->flatReference()->get()]);
        $homepage->save();
    }

    private function assertNoEmptyValues(array $flatReferences)
    {
        foreach ($flatReferences as $locale => $flatReferenceString) {
            if (!$flatReferenceString) {
                throw new \InvalidArgumentException('Homepage setting value cannot be empty. Value for locale ' . $locale . ' is missing.');
            }
        }
    }
}
