<?php

namespace App\Services;

use Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloundinaryService
{
    public function upload($file, $folder)
    {
        $image = Cloudinary::upload($file->getRealPath(), [
            'folder' => env('CLOUDINARY_FOLDER') . $folder
        ]);
        $url = $image->getSecurePath();

        return $url;
    }

    public function uploadFile($file, $folder)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $uniqueFileName = $originalName . '_' . time() . '.' . $extension;
        $uploadedFile = Cloudinary::upload($file->getRealPath(), [
            'resource_type' => 'raw', 
            'folder' => trim(env('CLOUDINARY_FOLDER'), '/') . '/' . trim($folder, '/'),
            'public_id' => pathinfo($uniqueFileName, PATHINFO_FILENAME), // Không kèm phần mở rộng trong public_id
            'format' => $extension, 
            'use_filename' => true,
            'unique_filename' => false,
        ]);
        return $uploadedFile->getSecurePath();
    }

    public function delete($url)
    {
        $publicId = $this->extractPublicIdFromUrl($url);
        Cloudinary::destroy($publicId);
        return true;
    }

    private function extractPublicIdFromUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);

        $result = Str::after($path, env('CL_ID'));

        $result = Str::beforeLast($result, '.');
        return $result;
    }
}
