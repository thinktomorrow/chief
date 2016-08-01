<?php

namespace Chief\Assets\Services;

class Util
{
    /**
     * @param string    $filename
     * @param              $basepath
     * @return null|string
     */
    public static function generateUniqueFilename($filename, $basepath)
    {
        $original_filename = $filename;
        $info = pathinfo($original_filename);
        $i = 1;

        while ( file_exists($basepath . '/' . $filename) )
        {
            $filename = basename($original_filename, '.' . $info['extension']) . $i . '.' . $info['extension'];
            $i++;
        }

        return $filename;
    }
}