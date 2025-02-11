<?php

namespace Thinktomorrow\Chief\Assets\App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;

class StoreFiles
{
    private CreateAsset $createAsset;

    /** @var string the media disk where the files should be stored. */
    private $disk = '';

    final public function __construct(CreateAsset $createAsset)
    {
        $this->createAsset = $createAsset;
    }

    public function handle(array $input): void
    {
        foreach (data_get($input, 'uploads', []) as $fileInput) {

            $filename = $this->sluggifyFilename($fileInput['originalName']);

            $uploadedFile = new UploadedFile($fileInput['path'], $filename, $fileInput['mimeType']);

            $this->createAsset
                ->uploadedFile($uploadedFile)
                ->filename($filename)
                ->save($this->getDisk());
        }
    }

    private function sluggifyFilename(string $filename): string
    {
        if (strpos($filename, '.') === false) {
            return $filename;
        }

        $extension = substr($filename, strrpos($filename, '.') + 1);
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return Str::slug($filename).'.'.$extension;
    }

    protected function setDisk(string $disk): void
    {
        $this->disk = $disk;
    }

    protected function getDisk(): string
    {
        return $this->disk;
    }
}
