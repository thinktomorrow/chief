<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Media\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Forms;

class AsyncFileUpload
{
    private Fields\Validation\FieldValidator $fieldValidator;

    public function __construct(Fields\Validation\FieldValidator $fieldValidator)
    {
        $this->fieldValidator = $fieldValidator;
    }

    /**
     * Upload a file via the file field. Keep in mind that here one file at a time
     * is upload asynchronously. This can be either a json object representing
     * base64 encoded image or a regular UploadedFile
     */
    public function upload(Model $model, string $fieldKey, UploadedFile|\stdClass $input, string $locale): Response
    {
        try {
            $field = Forms::make($model->fields())
                ->fillModel($model)
                ->getFields()
                ->find($fieldKey)
            ;

            if ($input instanceof UploadedFile) {
                $this->validateAsyncFileUpload($field, $locale, $input);
                $asset = AssetUploader::upload($input, $input->getClientOriginalName());
            } else {
                $this->validateAsyncSlimUpload($field, $locale, $input);
                $asset = AssetUploader::uploadFromBase64($input->output->image, $input->output->name);
            }

            $url = $field->isStoredOnPublicDisk()
                ? $asset->url()
                : ($field->generatesCustomUrl() ? $field->generateCustomUrl($asset) : '');

            return response()->json([
                'url' => $url,
                'filename' => $asset->filename(),
                'id' => $asset->id,
                'mimetype' => $asset->getMimeType(),
                'size' => $asset->getSize(),
                'isImage' => ('image' == $asset->getExtensionType()),
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

    private function validateAsyncFileUpload(Fields\Field $field, string $locale, UploadedFile $uploadedFile): void
    {
        $this->validateUpload($field, $locale, $uploadedFile);
    }

    private function validateAsyncSlimUpload(Fields\Field $field, string $locale, $imagePayload): void
    {
        $this->validateUpload($field, $locale, json_encode($imagePayload));
    }

    private function validateUpload(Fields\Field $field, string $locale, UploadedFile|string $input): void
    {
        // TODO: cant we use the default validation for this???

        // Convert this request to an expected format for our validation rules.
        // validation rules expects something as [files.avatar.nl => [payload]]
        $payload = [];

        $dottedFieldName = Fields\Common\FormKey::replaceBracketsByDots($field->getName($locale));
        Arr::set($payload, $dottedFieldName, [$input]);

        $this->fieldValidator->handle(Fields::make([$field]), $payload);
    }
}
