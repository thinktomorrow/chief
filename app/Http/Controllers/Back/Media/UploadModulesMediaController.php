<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Media;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class UploadModulesMediaController extends Controller
{
    /**
     * Upload a file via redactor editor. Keep in mind
     * that here one file at a time is accepted
     */
    public function store(Request $request, $id)
    {
        $uploads = $request->file('file');
        $model = Module::findOrFail($id);

        if (empty($uploads)) {
            return response()->json([
                'error' => true,
                'messages' => 'Geen afbeelding opgeladen.',
            ], 500);
        }

        $responseContent = [];

        foreach ($uploads as $upload) {
            if (! $asset = AssetUploader::upload($upload)) {
                return response()->json([
                    'error' => true,
                    'messages' => 'Afbeelding kan niet worden opgeladen.',
                ], 500);
            }

            app(AddAsset::class)->add($model, $asset, MediaType::CONTENT, 'nl');

            $responseContent['file-'.$asset->id] = [
                'url' => $asset->url(),
                'id' => $asset->id,
            ];
        }

        return response()->json($responseContent, 201);
    }
}
