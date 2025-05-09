<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles\Vimeo;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\Driver;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;
use Thinktomorrow\Chief\Assets\App\FileApplication;

class VimeoDriver implements Driver
{
    private CreateAsset $createAsset;

    private FileApplication $fileApplication;

    public function __construct(CreateAsset $createAsset, FileApplication $fileApplication)
    {
        $this->createAsset = $createAsset;
        $this->fileApplication = $fileApplication;
    }

    public function createAsset(string $idOrUrl): AssetContract
    {
        $response = $this->getInfo($idOrUrl);

        // Create preview thumb as local reference to the external link
        $asset = $this->createPreviewThumbByResponse($response);

        $this->updateAssetDataByResponse($asset, $response);

        $asset->save();

        return $asset;
    }

    /**
     * This will fetch the actual file values via the driver API and
     * updates the media database record with the new values
     */
    public function updateAsset(AssetContract $asset, string $id): AssetContract
    {
        $response = $this->getInfo($id);

        $this->updatePreviewThumbByResponse($asset, $response);

        $this->updateAssetDataByResponse($asset, $response);

        $asset->save();

        return $asset;
    }

    /**
     * Attempt to parse an url input to id.
     * If url is not valid for this driver or Id cannot be extracted, null is returned
     */
    private function getInfo(string $idOrUrl): array
    {
        $query = str_contains($idOrUrl, 'http') ? $idOrUrl : 'https://vimeo.com/'.$idOrUrl;

        try {
            $response = file_get_contents('https://vimeo.com/api/oembed.json?url='.urlencode($query));

            return json_decode($response, true);
        } catch (\ErrorException $e) {
            throw ValidationException::withMessages(['driverId' => 'De opgegeven id of link is geen geldige Vimeo verwijzing: '.'['.$idOrUrl.']']);
        }
        // ERROR/ file_get_contents(): Failed to enable crypto ???

        // TODO: handle these errors
        // 304	The video hasn't changed since the date given in the If-Modified-Since HTTP header.
        // 403	Embed permissions are disabled for this video, so you can't embed it.
        // 404	You aren't able to access the video because of privacy or permissions issues, or because the video is still transcoding.

    }

    public function getCreateFormLabel(): string
    {
        return 'Video ID of URL';
    }

    public function getCreateFormDescription(): string
    {
        return '
            Nadat je een video geüpload hebt naar Vimeo, kan je de ID van die video hier ingeven.
            De video ID kan je terugvinden in de URL van de video: https://vimeo.com/video/<b>524933864</b>.
            Daarnaast kan je ook de volledige URL van de video ingeven.
        ';
    }

    private function createPreviewThumbByResponse(array $oEmbedResponse): AssetContract
    {
        $fileName = Str::slug($oEmbedResponse['title']).'.webp';
        $thumbUrl = $oEmbedResponse['thumbnail_url'].'.webp';

        $asset = $this->createAsset
            ->url($thumbUrl)
            ->filename($fileName)
            ->save();

        $asset->asset_type = array_search(static::class, DriverFactory::$map);
        $asset->save();

        return $asset;
    }

    private function updateAssetDataByResponse(AssetContract $asset, array $response)
    {
        $asset->asset_type = array_search(static::class, DriverFactory::$map);

        $asset->setData('external.type', array_search(static::class, DriverFactory::$map));
        $asset->setData('external.id', $response['video_id']);
        $asset->setData('external.width', $response['width']);
        $asset->setData('external.height', $response['height']);
        $asset->setData('external.duration', $response['duration']);
        $asset->setData('external.title', $response['title']);
        $asset->setData('external.filetype', $response['type']); // video
        $asset->setData('external.mimetype', 'video/vimeo');
    }

    private function updatePreviewThumbByResponse(AssetContract $asset, array $oEmbedResponse): void
    {
        $fileName = Str::slug($oEmbedResponse['title']).'.webp';
        $thumbUrl = $oEmbedResponse['thumbnail_url'].'.webp';

        $media = $asset->getFirstMedia();
        $media->file_name = $fileName;

        $media->save();

        $this->fileApplication->replaceMediaByUrl($asset->id, $thumbUrl);
    }
}
