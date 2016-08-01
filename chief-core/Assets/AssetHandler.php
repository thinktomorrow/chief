<?php namespace Chief\Assets;

use Illuminate\Support\Facades\App;
use Intervention\Image\Exception\InvalidImageDataStringException;

trait AssetHandler
{
    protected function extractExtension($filename)
    {
        return substr($filename,strrpos($filename,'.')+1);
    }

    /**
     * Restrict size of image
     *
     * @param   string $filepath
     * @param null     $width
     * @param null     $height
     */
    protected function restrictWidthAndHeight($filepath,$width = null,$height = null)
    {
        $image = App::make('Intervention\Image\ImageManagerStatic');

        $image->make($filepath)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->interlace()
            ->save();

        // Release memory on 25/04/2014 since we received a lot of memory errors since march 2015
        // 'Allowed memory size of 134217728 bytes exhausted (tried to allocate 24000 bytes)' in /home/youngpro/domains/youngprozzz.com/public_html/www/vendor/intervention/image/src/Intervention/Image/Gd/Decoder.php:34
        // ImageManagerStatic does not have class destroy
        //$image->destroy();

    }
}