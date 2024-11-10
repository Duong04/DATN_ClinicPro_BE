<?php 
namespace App\Services;

use App\Repositories\MedicalHistory\MedicalHistoryRepositoryInterface;
use App\Services\CloundinaryService;
use App\Models\File;
use App\Http\Resources\MedicalHistoryResource;

class MedicalHistoryService {
    private $medicalHistoryRepository;
    private $cloundinaryService;

    public function __construct(MedicalHistoryRepositoryInterface $medicalHistoryRepository, CloundinaryService $cloundinaryService) {
        $this->medicalHistoryRepository = $medicalHistoryRepository;
        $this->cloundinaryService = $cloundinaryService;
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $medicalHistories = $this->medicalHistoryRepository->paginate($limit, $q);

            if ($limit) {
                return response()->json([
                    'data' => MedicalHistoryResource::collection($medicalHistories->items()),
                    'prev_page_url' => $medicalHistories->previousPageUrl(),
                    'next_page_url' => $medicalHistories->nextPageUrl(),
                    'total' => $medicalHistories->total()
                ], 200);
            }
    
            return response()->json(['data' => MedicalHistoryResource::collection($medicalHistories)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function findById($id) {
        try {
            $data = $this->medicalHistoryRepository->find($id);
            if (empty($data)) {
                return response()->json(['error' => 'Không tìm thấy lịch sử bệnh án'], 404);
            }

            return response()->json(['data' => new MedicalHistoryResource($data)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();

            $medicalHistory = $this->medicalHistoryRepository->create($data);
            if (isset($data['files']) && is_array($data['files'])) {
                foreach ($data['files'] as $fileItem) {
                    $fileItem['medical_history_id'] = $medicalHistory->id;
                    File::create($fileItem);
                }
            }

            $medicalHistory = $this->medicalHistoryRepository->find($medicalHistory->id);

            return response()->json(['message' => 'Tạo lịch sử bệnh án thành công!', 'data' => new MedicalHistoryResource($medicalHistory)], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    
    public function getByPatientId($patient_id) {
        try {
            $medicalHistories = $this->medicalHistoryRepository->getByPatientId($patient_id);
            if (count($medicalHistories) <= 0) {
                return response()->json(['error' => 'Không tìm thấy hồ sơ bệnh án của người này'], 404);
            }

            return response()->json(['data' => MedicalHistoryResource::collection($medicalHistories)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();
            $this->medicalHistoryRepository->update($id, $data);

            if (isset($data['files']) && is_array($data['files'])) {
                foreach ($data['files'] as $fileItem) {

                    if (isset($fileItem['id'])) {
                        $checkFile = File::find($fileItem['id']);
                        if (!empty($checkFile)) {
                            $checkFile->update($fileItem);
                        }
                    }else {
                        $fileItem['medical_history_id'] = $id;
                        File::create($fileItem);
                    }

                }
            }

            return response()->json(['message' => 'Cập nhật lịch sử bệnh án thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function delete($id) {
        try {
            $this->medicalHistoryRepository->delete($id);

            return response()->json(['error' => 'Xóa lịch sử bệnh án thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Không tìm thấy lịch sử bệnh án!'], 404);
        }
    }
}