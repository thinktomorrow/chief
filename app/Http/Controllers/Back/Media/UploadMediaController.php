<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Models\AssetUploader;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Pages\Page;

class UploadMediaController extends Controller
{
    /**
     * Upload a file via redactor editor. Keep in mind
     * that here one file at a time is accepted
     */
    public function store(Request $request, $owner_type, $owner_id)
    {
        $upload = $request->file('file');
        $model = $this->guessModel($owner_type, $owner_id);

        if(empty($upload)) return response()->json([
            'error' => true,
            'messages' => 'Geen afbeelding opgeladen.',
        ], 500);

        if( ! $asset = AssetUploader::upload($upload)) {
            return response()->json([
                'error' => true,
                'messages' => 'Afbeelding kan niet worden opgeladen.',
            ], 500);
        }

        $asset->attachToModel($model, MediaType::CONTENT);

//        return response()->json([
//                'url' => $asset->getFileUrl(),
//                'id' => $asset->id,
//        ], 200);

        return response()->json([
            'file' => [
                'url' => $asset->getFileUrl(),
                'id' => $asset->id,
            ]
        ], 200);

    }

    private function guessModel($owner_type, $owner_id): Model
    {
        // TODO: mapping in config??
        $ownerClassName = null;

        if($owner_type == 'page') {
            $ownerClassName = Page::class;
        }

        if(!class_exists($ownerClassName)) {
            throw new \Exception('Invalid model type. [' . $ownerClassName . '] does not exist as class.');
        }

        // TODO: for the moment we only use the page id but this should change
        return $ownerClassName::ignoreCollection()->findOrFail($owner_id);
    }

}
