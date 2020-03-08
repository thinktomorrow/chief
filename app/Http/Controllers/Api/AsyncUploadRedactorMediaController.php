<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class AsyncUploadRedactorMediaController extends Controller
{
    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    /**
     * Upload a file via redactor editor. Keep in mind
     * that here one file at a time is accepted
     *
     * @param string $key
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Thinktomorrow\AssetLibrary\Exceptions\AssetUploadException
     */
    public function upload(string $key, $id, Request $request)
    {
        $files = $request->input('files', []);
        $model = $this->managers->findByKey($key, $id)->existingModel();

        if (!is_array($files) || empty($files)) {
            return response()->json([
                'error'    => true,
                'messages' => 'Geen afbeelding opgeladen.',
            ], 200);
        }

        $responseContent = [];

        foreach ($files as $filePayload) {
            $base64EncodedFile = $filePayload['data'];
            $filename = $filePayload['filename'];

            if (! $asset = AssetUploader::uploadFromBase64($base64EncodedFile, $filename)) {
                $responseContent['file-' . rand(1-999)] = [
                    'error'    => true,
                    'messages' => 'Afbeelding [' . $filename . '] kan niet worden opgeladen.',
                ];
                continue;
            }

            app(AddAsset::class)->add($model, $asset, MediaType::CONTENT, $request->input('locale', app()->getLocale()));

            $responseContent['file-' . $asset->id] = [
                'url' => $asset->url(),
                'id'  => $asset->id,
            ];
        }

        return response()->json($responseContent, 201);
    }
}
