<?php 
namespace App\Services;

use App\Repositories\MedicalHistory\MedicalHistoryRepositoryInterface;
use App\Services\CloundinaryService;

class MedicalHistoryService {
    private $medicalHistoryService;
    private $cloundinaryService;

    public function __construct(MedicalHistoryRepositoryInterface $medicalHistoryRepository, CloundinaryService $cloundinaryService) {
        $this->medicalHistoryService = $medicalHistoryRepository;
        $this->cloundinaryService = $cloundinaryService;
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $medicalHistories = $this->medicalHistoryService->paginate($limit, $q);

            if ($limit) {
                return response()->json([
                    'data' => $medicalHistories->items(),
                    'prev_page_url' => $medicalHistories->previousPageUrl(),
                    'next_page_url' => $medicalHistories->nextPageUrl(),
                    'total' => $medicalHistories->total()
                ], 200);
            }
    
            return response()->json(['data' => $medicalHistories], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function findById($id) {
        try {
            $data = $this->medicalHistoryService->find($id);
            if (empty($data)) {
                return response()->json(['error' => 'Không tìm thấy lịch sử bệnh án'], 404);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();

            $medicalHistory = $this->medicalHistoryService->create($data);
            if (isset($data['files']) && is_array($data['files'])) {
                foreach ($data['files'] as $index => $fileItem) {
                    $description = $fileItem['description'] ?? null;
    
                    if ($request->hasFile("files.$index.file")) {
                        $file = $request->file("files.$index.file");
                        $folder = 'medical-histories';
                        $path = $this->cloundinaryService->upload($file, $folder);
    
                        $fileData = [
                            'file' => $path,
                            'description' => $description,
                        ];
    
                        $medicalHistory->files()->create($fileData);
                    }
                }
            }

            return response()->json(['message' => 'Tạo lịch sử bệnh án thành công!', 'data' => $medicalHistory], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();
            $this->medicalHistoryService->update($id, $data);

            return response()->json(['message' => 'Cập nhật lịch sử bệnh án thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function delete($id) {
        try {
            $this->medicalHistoryService->delete($id);

            return response()->json(['error' => 'Xóa lịch sử bệnh án thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Không tìm thấy lịch sử bệnh án!'], 404);
        }
    }
}