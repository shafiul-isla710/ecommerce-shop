<?php

namespace App\Manager;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadManager
{

    public const defaultImage = 'images/defaultImage.png';

    public static function imageUpload(string $name, int $width, int $height, string $path, string $file)
    {
        $path = public_path($path . $name);
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->resize($width, $height)->save($path);
        return $name;
    }
}
