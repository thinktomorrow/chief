<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Media;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Media\MediaType;
use Illuminate\Support\Facades\Validator;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\AssetLibrary\Models\AssetUploader;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class UploadManagersMediaController extends Controller
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
    public function store(string $key, $id, Request $request)
    {
        $uploads = $request->file('file');

        $model = $this->managers->findByKey($key, $id)->model();

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

            $asset->attachToModel($model, MediaType::CONTENT);

            $responseContent['file-'.$asset->id] = [
                'url' => $asset->getFileUrl(),
                'id' => $asset->id,
            ];
        }

        return response()->json($responseContent, 201);
    }
}
