<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles\Youtube;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\Driver;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;
use Thinktomorrow\Chief\Assets\App\FileApplication;

class YoutubeDriver implements Driver
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

        $id = str_contains($idOrUrl, 'http') ? $this->getYoutubeIdFromUrl($idOrUrl) : $idOrUrl;

        // Create preview thumb as local reference to the external link
        $asset = $this->createPreviewThumbByResponse($response);

        $this->updateAssetDataByResponse($asset, $id, $response);

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

        $this->updateAssetDataByResponse($asset, $id, $response);

        $asset->save();

        return $asset;
    }

    /**
     * Attempt to parse an url input to id.
     * If url is not valid for this driver or Id cannot be extracted, null is returned
     */
    private function getInfo(string $idOrUrl): array
    {
        $query = str_contains($idOrUrl, 'http') ? $idOrUrl : 'https://youtube.com/watch?v=' . $idOrUrl;

        try {
            $response = file_get_contents('https://youtube.com/oembed?format=json&url='.urlencode($query));

            return json_decode($response, true);
        } catch(\ErrorException $e) {
            throw ValidationException::withMessages(['driverId' => 'De opgegeven id of link is geen geldige Youtube verwijzing: ' . '['.$idOrUrl.']']);
        }
    }

    public function getCreateFormLabel(): string
    {
        return 'Video ID of URL';
    }

    public function getCreateFormDescription(): string
    {
        return '
            Nadat je een video geüpload hebt naar Youtube, kan je de ID van die video hier ingeven.
            De video ID kan je terugvinden in de URL van de video: https://youtube.com/watch?v=<b>524933864</b>.
            Daarnaast kan je ook de volledige URL van de video ingeven.
        ';
    }

    private function createPreviewThumbByResponse(array $oEmbedResponse): AssetContract
    {
        $fileName = Str::slug($oEmbedResponse['title']) . '.jpg';
        $thumbUrl = $oEmbedResponse['thumbnail_url'];

        $asset = $this->createAsset
            ->url($thumbUrl)
            ->filename($fileName)
            ->save();

        $asset->asset_type = array_search(static::class, DriverFactory::$map);

        $asset->save();

        return $asset;
    }

    // Duration is not included in the oembed response
    private function updateAssetDataByResponse(AssetContract $asset, string $id, array $response)
    {
        $asset->asset_type = array_search(static::class, DriverFactory::$map);

        $asset->setData('external.type', array_search(static::class, DriverFactory::$map));
        $asset->setData('external.id', $id);
        $asset->setData('external.width', $response['width']);
        $asset->setData('external.height', $response['height']);
        $asset->setData('external.title', $response['title']);
        $asset->setData('external.filetype', $response['type']);
        $asset->setData('external.mimetype', 'video/youtube');
    }

    private function updatePreviewThumbByResponse(AssetContract $asset, array $oEmbedResponse): void
    {
        $fileName = Str::slug($oEmbedResponse['title']) . '.jpg';
        $thumbUrl = $oEmbedResponse['thumbnail_url'];

        $media = $asset->getFirstMedia();
        $media->file_name = $fileName;

        $media->save();

        $this->fileApplication->replaceMediaByUrl($asset->id, $thumbUrl);
    }


    /**
     * Get Youtube video ID from URL
     * Source: https://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id#answer-17799714
     *
     * @param string $url
     * @return mixed Youtube video ID or FALSE if not found
     */
    private function getYoutubeIdFromUrl($url)
    {
        $parts = parse_url($url);

        if (isset($parts['query'])) {
            parse_str($parts['query'], $qs);

            if (isset($qs['v'])) {
                return $qs['v'];
            } elseif (isset($qs['vi'])) {
                return $qs['vi'];
            }
        }

        if(isset($parts['path'])) {
            $path = explode('/', trim($parts['path'], '/'));

            return $path[count($path) - 1];
        }

        return false;
    }
}
