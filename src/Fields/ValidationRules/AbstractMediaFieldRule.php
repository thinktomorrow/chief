<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

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
}
