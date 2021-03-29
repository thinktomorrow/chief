<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\FieldName;
use Thinktomorrow\Chief\Management\Managers;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Fields\Types\FileField;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Fields\Validation\FieldValidator;

class AsyncUploadFileMediaController extends Controller
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
        $uploadedFile = $request->file('file');
        $locale = $request->input('locale');
        $managerKey = $request->input('managerKey');
        $fieldKey = $request->input('fieldKey');

        $field = $this->getFileField($managerKey, $fieldKey);

        try {
            $this->validateUpload(
                $field,
                $locale,
                $uploadedFile
            );

            $asset = AssetUploader::upload($uploadedFile, $uploadedFile->getClientOriginalName(), 'default', $field->getStorageDisk() ?: '');

            $url = $field->isStoredOnPublicDisk()
                ? $asset->url()
                : ($field->generatesCustomUrl() ? $field->generateCustomUrl($asset) : '');

            return response()->json([
                'url'      => $url,
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

    protected function validateUpload(FileField $field, string $locale, UploadedFile $uploadedFile)
    {
        // Convert this request to an expected format for our validation rules.
        // validation rules expects something as [images.avatar.nl => [payload]]
        $validationPayload = [];
        $dottedFieldName = FieldName::fromString($field->getName($locale))->get();
        Arr::set($validationPayload, $dottedFieldName, [$uploadedFile]); // encode it back as a string just as a normal slim request

        $this->fieldValidator->handle(new Fields([$field]), $validationPayload);
    }

    private function getFileField(string $managerKey, string $fieldKey): FileField
    {
        $manager = $this->managers->findByKey($managerKey);

        return $manager->fields()[$fieldKey];
    }
}
