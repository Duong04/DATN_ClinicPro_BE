<?php 
namespace App\Services;
use Kreait\Firebase\Factory;

class FirebaseService {
    private $storage;

    public function __construct()
    {
        $firebase = (new Factory)
        ->withServiceAccount(storage_path('app/firebase-service-account.json'))
        ->withProjectId(env('FIREBASE_PROJECT_ID'));

        $this->storage = $firebase->createStorage();
    }

    public function uploadFile($file, $folder)
    {
        $bucket = $this->storage->getBucket();

        if (!$file || !$file->isValid()) {
            throw new \Exception('Tệp tải lên không hợp lệ.');
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $folder . '/' . $fileName;

        $stream = fopen($file->getRealPath(), 'r');
        if (!$stream) {
            throw new \Exception('Không thể mở file: ' . $file->getRealPath());
        }

        try {
            $object = $bucket->upload($stream, [
                'name' => $path,
            ]);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $bucketName = $this->storage->getBucket()->info()['name'];
        return "https://firebasestorage.googleapis.com/v0/b/{$bucketName}/o/" . urlencode($path) . "?alt=media";
    }



}