<?php namespace Chief\Assets;

use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Chief\Assets\Services\FileInfo;
use Chief\Assets\Services\FileUpload;
use Chief\Assets\Services\Util;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class BaseAsset {

    protected $filename;
    protected $basepath;
    protected $basename;
    protected $baseurl;

    /**
     * Remove asset from disk
     *
     * @return void
     */
    public function remove()
    {
        if ( $this->exists() )
        {
            @chmod($this->getPath(), 0777);
            @unlink($this->getPath());
        }
    }

    /**
     * Get folder path
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->basepath;
    }

    public function getBasepath()
    {
        return $this->basepath;
    }

    /**
     * Get filename
     *
     * @return  string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filename
     *
     * @param   $filename
     * @return  void
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get full asset path
     *
     * @return string
     */
    public function getPath()
    {
        return rtrim($this->basepath . '/' . $this->filename, '/');
    }

    /**
     * Get full asset url
     *
     * @return string
     */
    public function getUrl()
    {
        return rtrim($this->baseurl . '/' . $this->filename, '/');
    }

    public function getFilesize()
    {
        return $this->exists() ? filesize($this->getPath()) : 0;
    }

    /**
     * Check if a stylesheet file already exists
     *
     * @return  bool
     */
    public function exists()
    {
        return (file_exists($this->getPath()) and is_file($this->getPath()));
    }

    public function setBasename($basename)
    {
        $this->basename = $basename;

        return $this;
    }

    /**
     * Upload new asset to disk
     *
     * @param  UploadedFile $uploadFile
     * @param  string $filename
     * @param bool $force_unique force unique filename is one is passed
     * @return $this
     * @throws \Exception
     */
    public function upload(UploadedFile $uploadFile = null, $filename = null, $force_unique = true)
    {
        $this->createFolderPath();

        $uploader = app()->make(FileUpload::class);

        if ( !$uploader->upload($uploadFile,$this->basepath,$filename,$this->basename,$force_unique) )
        {
            throw new \Exception($uploader->getError());
        }

        $this->filename = $uploader->getFilename();

        return $this;
    }

    public function dataURI($dataURI, $filename, $force_unique = true)
    {
        $binary = $this->decodeBase64($dataURI);

        return $this->importBinary($binary,$filename, $force_unique);
    }

    private function decodeBase64($base_64_image)
    {
        $base_64_image = str_replace('data:image/jpeg;base64,', '', $base_64_image);
        $base_64_image = str_replace('data:image/jpg;base64,', '', $base_64_image);
        $base_64_image = str_replace('data:image/png;base64,', '', $base_64_image);

        return base64_decode($base_64_image);
    }

    /**
     * Copy existing file to the asset location
     *
     * @param $filepath
     * @param null $filename
     * @param bool $force_unique
     * @return $this
     */
    public function import($filepath, $filename = null, $force_unique = false)
    {
        if(!file_exists($filepath))
        {
            throw new InvalidArgumentException('Asset file import failed. Invalid filepath ['.$filepath.'] given');
        }

        $this->createFolderPath();

        if(!$filename)
        {
            $filename = substr($filepath,strrpos($filepath,'/'));

            // If filename is not passed, we always check for unique filename
            $force_unique = true;
        }

        if($force_unique) $filename = Util::generateUniqueFilename($filename,$this->basepath);

        copy($filepath,$this->basepath.'/'.$filename);

        $this->setFilename($filename);

        return $this;
    }

    /**
     * Copy existing file to the asset location and force unique name
     *
     * @param $filepath
     * @param null $filename
     * @return $this
     */
    public function importUnique($filepath,$filename = null)
    {
        return $this->import($filepath,$filename,true);
    }

    /**
     * Copy existing file to the asset location
     *
     * @param $binary
     * @param null $filename
     * @param bool $force_unique
     * @return $this
     */
    public function importBinary($binary, $filename, $force_unique = false)
    {
        $this->createFolderPath();

        if($force_unique) $filename = Util::generateUniqueFilename($filename,$this->basepath);

        file_put_contents($this->basepath.'/'.$filename,$binary);

        $this->setFilename($filename);

        return $this;
    }

    /**
     * Create basepath
     *
     * @return  void
     */
    protected function createFolderPath()
    {
        // Make sure our folder exists
        if ( !is_dir($this->basepath) )
        {
            @mkdir($this->basepath, 0777, true);
        }
    }

    /**
     * Verify the asset is an image
     *
     * @return bool
     */
    public function isImage()
    {
        return !FileInfo::make($this->getPath())->isImage();
    }

    public function resize($width = null,$height = null)
    {
        $img = ImageManagerStatic::make($this->getPath());

        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $img->interlace();
        $img->save();

        return $this;
    }

    /**
     * Copy from an existing asset
     *
     * @param Asset $asset
     * @param null $filename
     * @param bool|false $force_unique
     * @return $this
     */
    public function copyAsset(Asset $asset,$filename = null, $force_unique = false)
    {
        if(!$filename)
        {
            $filename = $asset->getFilename();

            // If filename is not passed, we always check for unique filename
            $force_unique = true;
        }

        if($force_unique) $filename = Util::generateUniqueFilename($filename,$this->basepath);

        copy($asset->getPath(),$this->basepath.'/'.$filename);

        $this->setFilename($filename);

        return $this;
    }


}