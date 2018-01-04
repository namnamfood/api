<?php
/**
 * Developed by Caspian Soft.
 *
 * Description:
 *
 * @author Orkhan Alirzayev
 * @date 1/4/18
 */

namespace App\Helpers;


use Intervention\Image\Facades\Image;

class ImageMaker
{
    public static function upload($file, $location, $filename)
    {
        // Resize properties from Intervention Image Package
        Image::make($file)->fit(400)->save();
        $file->storeAs($location . '/', $filename, 'uploads');
    }
}