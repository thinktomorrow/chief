<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Support\Str;
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
