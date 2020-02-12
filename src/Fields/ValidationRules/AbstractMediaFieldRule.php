<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Thinktomorrow\Chief\Media\Application\MediaRequest;

abstract class AbstractMediaFieldRule
{
    use ValidatesAttributes,
        ValidatesExistingAssetAttributes;

    protected function normalizePayload($value): array
    {
        $payload = $this->emptyPayload();

        if(!$value || !is_array($value)) return $payload;

        foreach([MediaRequest::NEW, MediaRequest::REPLACE, MediaRequest::DETACH] as $action) {
            if(isset($value[$action])){
                $payload[$action] = $value[$action];
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
