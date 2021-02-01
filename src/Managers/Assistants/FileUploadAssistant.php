<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldName;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;

trait FileUploadAssistant
{
    abstract protected function fieldsModel($id);

    abstract protected function fieldValidator(): FieldValidator;

    public function routesFileUploadAssistant(): array
    {
        return [
            ManagedRoute::post('asyncUploadFile', '{id}/asyncUploadFile/{fieldkey}'),
        ];
    }

    /**
     * Upload a file via the file field. Keep in mind
     * that here one file at a time is upload asynchronously
     */
    public function asyncUploadFile(Request $request, $id, $fieldKey)
    {
        $uploadedFile = $request->file('file');
        $locale = $request->input('locale');
        try {
            $model = $this->fieldsModel($id);
            $field = $model->fields()->find($fieldKey);

            $this->validateAsyncFileUpload(
                $field,
                $locale,
                $uploadedFile
            );

            $asset = AssetUploader::upload($uploadedFile, $uploadedFile->getClientOriginalName());

            return response()->json([
                'url'      => $asset->url(),
                'filename' => $asset->filename(),
                'id'       => $asset->id,
                'mimetype' => $asset->getMimeType(),
                'size'     => $asset->getSize(),
                'isImage'  => ($asset->getExtensionType() == 'image'),
            ], 201);
        } catch (ValidationException $e) {

            // Extract first error
            $errors = $e->errors();
            $errors = reset($errors);
            $firstError = reset($errors);

            return response()->json([
                'status'  => 'failure',
                'message' => $firstError,
            ], 422);
        } catch (PostTooLargeException $e) {
            return response()->json([
                'error'   => true, // required by redactor
                'message' => 'Te groot bestand...',
            ], 500);
        }
    }

    private function validateAsyncFileUpload(FileField $field, string $locale, UploadedFile $uploadedFile)
    {
        // Convert this request to an expected format for our validation rules.
        // validation rules expects something as [files.avatar.nl => [payload]]
        $validationPayload = [];

        $dottedFieldName = FieldName::fromString($field->getName($locale))->get();
        Arr::set($validationPayload, $dottedFieldName, [$uploadedFile]);

        $this->fieldValidator()->handle(new Fields([$field]), $validationPayload);
    }
}
