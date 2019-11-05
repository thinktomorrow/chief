<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Media;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;

class UploadPagesMediaController extends Controller
{
    /**
     * Upload a file via redactor editor. Keep in mind
     * that here one file at a time is accepted
     */
    public function store(Request $request, $id)
    {
        $uploads = $request->file('file');
        $model = Page::findOrFail($id);

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

            //TODO wiwysig uploads should be uploaded for the correct locale
            app(AddAsset::class)->add($model, $asset, MediaType::CONTENT, 'nl');

            $responseContent['file-'.$asset->id] = [
                'url' => $asset->url(),
                'id' => $asset->id,
            ];
        }

        return response()->json($responseContent, 201);
    }
}
