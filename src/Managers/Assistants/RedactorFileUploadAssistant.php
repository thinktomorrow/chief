<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\ManagedModels\Media\MediaType;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait RedactorFileUploadAssistant
{
    abstract protected function fieldsModel($id);
    abstract protected function fieldValidator(): FieldValidator;

    public function routesRedactorFileUploadAssistant(): array
    {
        return [
            ManagedRoute::post('asyncRedactorFileUpload', '{id}/asyncRedactorFileUpload'),
        ];
    }

    public function canRedactorFileUploadAssistant(string $action, $model =null): bool
    {
        if(in_array($action, ['asyncRedactorFileUpload'])) {
            return true;
        }

        return false;
    }

    /**
     * Upload a file via redactor editor.
     */
    public function asyncRedactorFileUpload(Request $request, $id)
    {
        $files = $request->input('files', []);
        $model = $this->fieldsModel($id);

        if (! is_array($files) || empty($files)) {
            return response()->json([
                'error' => true,
                'messages' => 'Geen afbeelding opgeladen.',
            ], 200);
        }

        $responseContent = [];

        foreach ($files as $filePayload) {
            $base64EncodedFile = $filePayload['data'];
            $filename = $filePayload['filename'];

            if (! $asset = AssetUploader::uploadFromBase64($base64EncodedFile, $filename)) {
                $responseContent['file-' . rand(1 - 999)] = [
                    'error' => true,
                    'messages' => 'Afbeelding [' . $filename . '] kan niet worden opgeladen.',
                ];

                continue;
            }

            app(AddAsset::class)->add($model, $asset, MediaType::CONTENT, $request->input('locale', app()->getLocale()));

            $responseContent['file-' . $asset->id] = [
                'url' => $asset->url(),
                'id' => $asset->id,
            ];
        }

        return response()->json($responseContent, 201);
    }
}
