<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Thinktomorrow\Chief\Media\Application\MediaRequest;

abstract class AbstractMediaFieldRule
{
    use ValidatesAttributes;
    use ValidatesExistingAssetAttributes;

    protected function normalizePayloadIncludingExistingMedia($value): array
    {
        return $this->normalizePayload($value, false);
    }

    protected function normalizePayload($value, $excludeExistingMedia = true): array
    {
        $payload = $this->emptyPayload();

        if(!$value || !is_array($value)) return $payload;

        foreach([MediaRequest::NEW, MediaRequest::REPLACE, MediaRequest::DETACH] as $action) {
            if(isset($value[$action])){

                // Front sometimes gives us a 0 => null array when an image is added and detached at the same time.
                // Here we check for these fake entries and exclude them.
                if(!is_array($value[$action]) || (count($value[$action]) === 1 && key($value[$action]) === 0 && is_null(reset($value[$action])))) {
                    continue;
                }

                $payload[$action] = $value[$action];

                // A replace NULL value passed from frontend is expected as a default so w'll need to remove it here to avoid unwanted validation.
                if($excludeExistingMedia && $action == MediaRequest::REPLACE && is_array($payload[$action])) {
                    foreach($payload[$action] as $k => $v) {
                        if(is_null($v)) unset($payload[$action][$k]);
                    }
                }
            }
        }

        return $payload;
    }

    private function emptyPayload(): array
    {
        return [
            MediaRequest::NEW => [],
            MediaRequest::REPLACE => [],
            MediaRequest::DETACH => [],
        ];
    }

    /**
     * Default getSize method
     *
     * Override the default getSize from ValidatesAttributes to avoid calls to a hasRule method
     * For media fields this is not needed anyways.
     *
     * @param $attribute
     * @param $value
     * @return bool|false|float|int
     */
    protected function getSize($attribute, $value)
    {
        if ($value instanceof File) {
            return $value->getSize() / 1024;
        }

        return mb_strlen($value);
    }
}
