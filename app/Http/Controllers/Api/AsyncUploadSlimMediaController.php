<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\FieldName;
use Thinktomorrow\Chief\Management\Managers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Fields\Validation\FieldValidator;

class AsyncUploadSlimMediaController extends Controller
{
    /** @var Managers */
    private $managers;

    /** @var FieldValidator */
    private $fieldValidator;

    public function __construct(Managers $managers, FieldValidator $fieldValidator)
    {
        $this->fieldValidator = $fieldValidator;
        $this->managers = $managers;
    }

    /**
     * Upload a file via redactor editor. Keep in mind
     * that here one file at a time is accepted
     */
    public function upload(Request $request)
    {
        $payload = $request->input('images', []);
        $imagePayload = reset($payload);
        $locale = key($imagePayload);
        $imagePayload = json_decode(reset($imagePayload[$locale])); // With the async upload, only one item is uploaded at a time.

        try {
            $this->validateUpload(
                $imagePayload->meta->managerKey,
                $imagePayload->meta->fieldKey,
                $locale,
                $imagePayload
            );

            $asset = AssetUploader::uploadFromBase64($imagePayload->output->image, $imagePayload->output->name);

            return response()->json([
                'url'      => $asset->url(),
                'filename' => $asset->filename(),
                'id'       => $asset->id,
            ], 201);
        } catch(ValidationException $e) {

            // Extract first error
            $errors = $e->errors();
            $errors = reset($errors);
            $firstError = reset($errors);

            return response()->json([
                'status' => 'failure',
                'message' => $firstError,
            ], 422);
        } catch(PostTooLargeException $e) {
            return response()->json([
                'error' => true, // required by redactor
                'message' => 'Te groot bestand...',
            ], 500);
        }

    }

    protected function validateUpload(string $managerKey, string $fieldKey, string $locale, $imagePayload)
    {
        $manager = $this->managers->findByKey($managerKey);
        $field = $manager->fields()[$fieldKey];

        // Convert this Slim request to an expected format for our validation rules.
        // validation rules expects something as [images.avatar.nl => [payload]]
        $validationPayload = [];
        $dottedFieldName = FieldName::fromString($field->getName($locale))->get();
        Arr::set($validationPayload, $dottedFieldName, [json_encode($imagePayload)]); // encode it back as a string just as a normal slim request

        $this->fieldValidator->handle(new Fields([$field]), $validationPayload);
    }
}
