<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldName;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait SlimImageUploadAssistant
{
    abstract protected function fieldsModel($id);
    abstract protected function fieldValidator(): FieldValidator;

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
     * that here one image at a time is uploaded asynchronously
     */
    public function asyncUploadSlimImage(Request $request, $fieldKey, $id = null)
    {
        $payload = $request->input('images', []);
        $rawImagePayload = reset($payload);

        $locale = key($rawImagePayload);

        /**
         * If locale not a string but an integer, we assume the passed payload is from a fragment field
         * Default payload is set as: images[images-hero][nl][new_67lpsJ] where Fragment fields have
         * a different setup: e.g. images[fragment][0][avatar][nl][new_f0O9Am]
         */
        if (! is_string($locale)) {
            $rawImagePayload = reset($rawImagePayload);
            $rawImagePayload = reset($rawImagePayload);
            $locale = key($rawImagePayload);
        }

        // With the async upload, only one item is uploaded at a time.
        $imagePayload = json_decode(reset($rawImagePayload[$locale]));

        try {
            $model = $id ? $this->fieldsModel($id) : new $this->managedModelClass();
            $field = Fields::make($model->fields())->find($fieldKey);

            $this->validateAsyncSlimUpload($field, $locale, $imagePayload);

            $asset = AssetUploader::uploadFromBase64($imagePayload->output->image, $imagePayload->output->name);

            $url = $field->isStoredOnPublicDisk()
                ? $asset->url()
                : ($field->generatesCustomUrl() ? $field->generateCustomUrl($asset) : '');

            return response()->json([
                'url' => $url,
                'filename' => $asset->filename(),
                'id' => $asset->id,
                'mimetype' => $asset->getMimeType(),
                'size' => $asset->getSize(),
            ], 201);
        } catch (ValidationException $e) {

            // Extract first error
            $errors = $e->errors();
            $errors = reset($errors);
            $firstError = reset($errors);

            return response()->json([
                'status' => 'failure',
                'message' => $firstError,
            ], 422);
        } catch (PostTooLargeException $e) {
            return response()->json([
                'error' => true, // required by redactor
                'message' => 'Te groot bestand...',
            ], 500);
        }
    }

    private function validateAsyncSlimUpload(ImageField $field, string $locale, $imagePayload): void
    {
        // Convert this Slim request to an expected format for our validation rules.
        // validation rules expects something as [images.avatar.nl => [payload]]
        $validationPayload = [];

        // encode the fieldreference back as a string just as a normal slim request
        $dottedFieldName = FieldName::fromString($field->getName($locale))->get();
        Arr::set($validationPayload, $dottedFieldName, [json_encode($imagePayload)]);

        $this->fieldValidator()->handle(new Fields([$field]), $validationPayload);
    }
}
