<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

trait UploadTrait
{

    public function uploadAllTypes($file, $directory, $width = null, $height = null)
    {
        if (!File::isDirectory('storage/images/' . $directory)) {
            File::makeDirectory('storage/images/' . $directory, 0777, true, true);
        }

        $fileMimeType = $file->getClientmimeType();
        $imageCheck = explode('/', $fileMimeType);

        if ($imageCheck[0] == 'image') {
            $allowedImagesMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($fileMimeType, $allowedImagesMimeTypes))
                return 'default.png';

            return $this->uploadImage($file, $directory, $width, $height);
        }

        $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/octet-stream'];
        if (!in_array($fileMimeType, $allowedMimeTypes))
            return  'default.png';

        return $this->uploadFile($file, $directory);
    }

    public function uploadFile($file, $directory)
    {
        $filename = time() . rand(1000000, 9999999) . '.' . $file->getClientOriginalExtension();
        $path = 'images/' . $directory;
        $file->storeAs($path, $filename);
        return $filename;
    }

    public function uploadImage($file, $directory, $width = null, $height = null)
    {
        $img        = Image::make($file)->orientate();
        $thumbsPath = 'storage/images/' . $directory;
        $name       = time() . '_' . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();

        if (null != $width && null != $height)
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

        $img->save($thumbsPath . '/' . $name);
        return (string) $name;
    }

    public function deleteFile($file_name, $directory = 'unknown'): void
    {
        if ($file_name && $file_name != 'default.png' && file_exists("storage/images/$directory/$file_name")) {
            unlink("storage/images/$directory/$file_name");
        }
    }

    public function defaultImage($directory)
    {
        return asset("/storage/images/$directory/default.png");
    }

    public static function getImage($name, $directory)
    {
        return asset("storage/images/$directory/" . $name);
    }
}
