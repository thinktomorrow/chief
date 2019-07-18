<?php
declare(strict_types=1);

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

        // Filter out empty values
//        foreach($flatReferences as $locale => $flatReferenceString)
//        {
//            if(!$flatReferenceString) {
//                unset($flatReferences[$locale]);
//            }
//        }

        foreach ($flatReferences as $locale => $flatReferenceString) {
            if (!$flatReferenceString) {

// TODO: when empty we'll remove the entry, we'll also want to revert to last redirect.

                if (isset($existingValues[$locale])) {
                    $flatReferenceInstance = FlatReferenceFactory::fromString(($existingValues[$locale]));
                    (new RevertUrlSlug($flatReferenceInstance->instance()))->handle($locale);

                    continue;
                }

                // Find the previous value so can alter it...
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
}
