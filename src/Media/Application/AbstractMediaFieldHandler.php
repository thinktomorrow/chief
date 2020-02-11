<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Types\MediaField;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\AssetLibrary\Application\ReplaceAsset;

abstract class AbstractMediaFieldHandler
{
    /** @var ReplaceAsset */
    protected $replaceAsset;

    /** @var AddAsset */
    protected $addAsset;

    /** @var DetachAsset */
    protected $detachAsset;

    final public function __construct(AddAsset $addAsset, ReplaceAsset $replaceAsset, DetachAsset $detachAsset)
    {
        $this->replaceAsset = $replaceAsset;
        $this->addAsset = $addAsset;
        $this->detachAsset = $detachAsset;
    }

    protected function mediaRequest(array $requests, MediaField $field, Request $request): MediaRequest
    {
        $mediaRequest = new MediaRequest();

        foreach($requests as $requestData){
            foreach($requestData as $locale => $filesPerLocale) {
                foreach($filesPerLocale as $action => $files) {
                    foreach($files as $k => $file) {
                        $mediaRequest->add($action, new MediaRequestInput(
                            $file, $locale, $field->getKey(), [
                                'index' => $k, // index key is used for replace method to indicate the current asset id
                                'existing_asset' => $this->refersToExistingAsset($file),
                            ]
                        ));
                    }
                }
            }
        }

        return $mediaRequest;
    }

    protected function refersToExistingAsset($value): bool
    {
        return is_int($value);
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function sluggifyFilename(string $filename): string
    {
        if(false === strpos($filename, '.')) return $filename;

        $extension = substr($filename, strrpos($filename, '.') + 1);
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return Str::slug($filename) . '.' . $extension;
    }
}
