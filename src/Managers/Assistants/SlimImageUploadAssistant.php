<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait SlimImageUploadAssistant
{
    public function routesSlimImageUploadAssistant(): array
    {
        return [
            ManagedRoute::post('asyncUploadSlimImage', '{fieldkey}/asyncUploadSlimImage/{id?}'),
        ];
    }

    public function canSlimImageUploadAssistant(string $action, $model = null): bool
    {
        if (in_array($action, ['asyncUploadSlimImage'])) {
            return true;
        }

        return false;
    }

    /**
     * Upload a file via the image field. Keep in mind
     * that here one image at a time is uploaded asynchronously.
     *
     * @param mixed      $fieldKey
     * @param null|mixed $id
     */
    public function asyncUploadSlimImage(Request $request, $fieldKey, $id = null)
    {
        $payload = $request->input('files', []);
        $rawImagePayload = reset($payload);

        $locale = key($rawImagePayload);

        /*
         * If locale not a string but an integer, we assume the passed payload is from a fragment field
         * Default payload is set as: files[images-hero][nl][new_67lpsJ] where Fragment fields have
         * a different setup: e.g. files[fragment][0][avatar][nl][new_f0O9Am]
         */
        if (!is_string($locale)) {
            $rawImagePayload = reset($rawImagePayload);
            $rawImagePayload = reset($rawImagePayload);
            $locale = key($rawImagePayload);
        }

        // With the async upload, only one item is uploaded at a time.
        $imagePayload = json_decode(reset($rawImagePayload[$locale]));

        $model = $id ? $this->fieldsModel($id) : new $this->managedModelClass();

        return app(Fields\Media\Application\AsyncFileUpload::class)->upload($model, $fieldKey, $imagePayload, $locale);
    }

    abstract protected function fieldsModel($id);
}
