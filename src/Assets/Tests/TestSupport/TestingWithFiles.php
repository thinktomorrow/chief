<?php

namespace Thinktomorrow\Chief\Assets\Tests\TestSupport;

use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\App\Actions\SaveFileField;
use Thinktomorrow\Chief\Resource\Resource;

trait TestingWithFiles
{
    protected function saveFileField(Resource $resource, HasAsset $model, $fieldKey, array $payload)
    {
        app(SaveFileField::class)->handle(
            $model,
            $resource->field($model, $fieldKey),
            [
                'files' => [
                    $fieldKey => $payload,
                ],
            ],
        );
    }
}
