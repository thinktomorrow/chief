<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Thinktomorrow\Chief\ManagedModels\Assistants\PageDefaults;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\PropagateUrlChange;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

trait PageDefaultWithNestableUrl
{
    use PageDefaults{
        baseUrlSegment as defaultBaseUrlSegment;
    }

    public static function bootPageDefaultWithNestableUrl()
    {
        static::saved(function (self $model) {
            if ($model->exists && $model->isDirty('parent_id')) {
                if ($model->parent_id == $model->getKey()) {
                    throw new \DomainException('Cannot assign itself as parent. Model ['.$model->getKey().'] is set with its own id ['.$model->parent_id.'] as parent_id.');
                }

                // When the parent changes or is removed, we need to be able to replace the base url segment belonging to this former parent.
                $formerParent = static::find($model->getOriginal('parent_id'));

                app(PropagateUrlChange::class)->handle($model, $formerParent);
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
