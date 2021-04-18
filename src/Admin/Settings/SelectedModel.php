<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Settings;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

final class SelectedModel
{
    /**
     * @param string $key
     * @param string|null $locale
     * @return mixed|null
     */
    public static function fromKey(string $key, string $locale = null)
    {
        if ($id = chiefSetting($key, $locale)) {
            return ModelReference::fromString($id)->instance();
        }

        return null;
    }

    public static function is($model, string $key, string $locale = null): bool
    {
        $id = chiefSetting($key, $locale);

        if (! $id || ! is_object($model) || ! $model instanceof ReferableModel) {
            return false;
        }

        return $model->modelReference()->is($id);
    }
}
