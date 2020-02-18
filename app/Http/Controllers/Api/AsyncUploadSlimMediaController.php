<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class AsyncUploadSlimMediaController extends Controller
{
    public function __construct() { }

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
    public function upload(Request $request)
    {
        $payload = $request->input('images', []);
        $imagePayload = reset($payload);

        $locale = key($imagePayload);

        // With the async upload, only one item is uploaded at a time.
        $imagePayload = json_decode(reset($imagePayload[$locale]));
        $base64Payload = $imagePayload->output->image;

        $asset = AssetUploader::uploadFromBase64($base64Payload, $imagePayload->output->name);

        return response()->json([
            'url' => $asset->url(),
            'filename' => $asset->filename(),
            'id'  => $asset->id,
        ], 201);
    }
}
