<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldName;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FileUploadAssistant
{
    abstract protected function fieldsModel($id);
    abstract protected function fieldValidator(): FieldValidator;

    public function routesFileUploadAssistant(): array
    {
        return [
            ManagedRoute::post('asyncUploadFile', '{fieldkey}/asyncUploadFile/{id?}'),
        ];
    }

    public function canFileUploadAssistant(string $action, $model = null): bool
    {
        if (in_array($action, ['asyncUploadFile'])) {
            return true;
        }

        return false;
    }

    /**
     * Upload a file via the file field. Keep in mind
     * that here one file at a time is upload asynchronously
     */
    public function asyncUploadFile(Request $request, $fieldKey, $id = null)
    {
        $uploadedFile = $request->file('file');
        $locale = $request->input('locale');

        try {
            $model = $id ? $this->fieldsModel($id) : new $this->managedModelClass();
            $field = Fields::make($model->fields())->find($fieldKey);

            $this->validateAsyncFileUpload($field, $locale, $uploadedFile);

            $asset = AssetUploader::upload($uploadedFile, $uploadedFile->getClientOriginalName());

            $url = $field->isStoredOnPublicDisk()
                ? $asset->url()
                : ($field->generatesCustomUrl() ? $field->generateCustomUrl($asset) : '');

            return response()->json([
                'url' => $url,
                'filename' => $asset->filename(),
                'id' => $asset->id,
                'mimetype' => $asset->getMimeType(),
                'size' => $asset->getSize(),
                'isImage' => ($asset->getExtensionType() == 'image'),
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

    private function validateAsyncFileUpload(FileField $field, string $locale, UploadedFile $uploadedFile): void
    {
        // Convert this request to an expected format for our validation rules.
        // validation rules expects something as [files.avatar.nl => [payload]]
        $validationPayload = [];

        $dottedFieldName = FieldName::fromString($field->getName($locale))->get();
        Arr::set($validationPayload, $dottedFieldName, [$uploadedFile]);

        $this->fieldValidator()->handle(new Fields([$field]), $validationPayload);
    }
}
