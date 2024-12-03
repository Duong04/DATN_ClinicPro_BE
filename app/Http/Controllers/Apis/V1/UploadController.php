<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CloundinaryService;
use App\Services\FirebaseService;

class UploadController extends Controller
{
    private $cloundinaryService;

    private $firebaseService;

    public function __construct(CloundinaryService $cloundinaryService, FirebaseService $firebaseService) {
        $this->cloundinaryService = $cloundinaryService;
        $this->firebaseService = $firebaseService;
    }

    public function uploadImage(Request $request) {
        try {
            $request->validate([
                'image' => 'required|image|max:10240', 
            ], [
                'image.required' => 'Trường image không được bỏ trống!',
                'image.image' => 'Trường image phải là 1 ảnh!',
                'image.max' => 'Ảnh không được vượt quá 10MB!'
            ]);
    
            $file = $request->file('image');
            $url = $this->cloundinaryService->upload($file, 'images');
    
            return response()->json([
                'message' => 'Upload thành công!',
                'url' => $url
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
    

    public function uploadImages(Request $request) {
        try {
            $request->validate([
                'images' => 'required|array',
                'images.*' => 'required|image|max:10240',
            ], [
                'images.required' => 'Trường images không được bỏ trống!',
                'images.*.image' => 'Mỗi trường trong images phải là 1 ảnh hợp lệ!',
                'images.*.max' => 'Mỗi ảnh không được vượt quá 10MB!'
            ]);
    
            $urls = [];
            foreach ($request->file('images') as $file) {
                $urls[] = $this->cloundinaryService->upload($file, 'images');
            }
    
            return response()->json([
                'message' => 'Upload thành công!',
                'urls' => $urls
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
    
    public function uploadFile(Request $request) {
        try {
            $request->validate([
                'file' => 'required|file|max:10240', 
            ], [
                'file.required' => 'Trường file không được bỏ trống!',
                'file.file' => 'Input phải là một file hợp lệ!',
                'file.max' => 'File không được vượt quá 5MB!'
            ]);
    
            $file = $request->file('file');
            $url = $this->firebaseService->uploadFile($file, 'files');
    
            return response()->json([
                'message' => 'Upload thành công!',
                'url' => $url
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
    

    public function uploadFiles(Request $request) {
        try {
            $request->validate([
                'files' => 'required|array',
                'files.*' => 'required|file|max:10240', 
            ], [
                'files.required' => 'Trường files không được bỏ trống!',
                'files.*.file' => 'Mỗi mục trong files phải là một file hợp lệ!',
                'files.*.max' => 'Mỗi file không được vượt quá 5MB!'
            ]);
    
            $urls = [];
            foreach ($request->file('files') as $file) {
                $urls[] = $this->firebaseService->uploadFile($file, 'files');
            }
    
            return response()->json([
                'message' => 'Upload thành công!',
                'urls' => $urls
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
    
    
}
