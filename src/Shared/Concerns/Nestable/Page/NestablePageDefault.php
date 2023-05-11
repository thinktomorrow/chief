<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Thinktomorrow\Chief\ManagedModels\Assistants\PageDefaults;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableDefault;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

trait NestablePageDefault
{
    use PageDefaults{
        baseUrlSegment as defaultBaseUrlSegment;
    }
    use NestableDefault;

    public static function bootNestablePageDefault()
    {
        static::saved(function (self $model) {
            if ($model->exists && $model->isDirty('parent_id')) {
                if ($model->parent_id == $model->getKey()) {
                    throw new \DomainException('Cannot assign itself as parent. Model ['.$model->getKey().'] is set with its own id ['.$model->parent_id.'] as parent_id.');
                }

                app(PropagateUrlChange::class)->handle($model);
            }
        });
    }

    public function baseUrlSegment(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($parent = $this->getParent()) {
            return UrlRecord::findSlugByModel($parent, $locale);
        }

        return $this->defaultBaseUrlSegment($locale);
    }
}
