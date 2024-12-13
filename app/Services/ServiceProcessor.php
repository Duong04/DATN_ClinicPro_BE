<?php 
namespace App\Services;

use App\Repositories\Service\ServiceRepositoryInterface;

class ServiceProcessor {
    private $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository) {
        $this->serviceRepository = $serviceRepository;
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $services = $this->serviceRepository->paginate($limit, $q);

            if ($limit) {
                return response()->json([
                    'success' => true,
                    'data' => $services->items(),
                    'prev_page_url' => $services->previousPageUrl(),
                    'next_page_url' => $services->nextPageUrl(),
                    'total' => $services->total(),
                    'total_pages' => $services->lastPage()
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $services
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(), 
            ], 400);
        }
    }

    public function findById($id) {
        try {
            $service = $this->serviceRepository->find($id);

            if (empty($service)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy dịch vụ!'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $service
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();

            $service = $this->serviceRepository->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Tạo dịch vụ thành công!',
                'data' => $service
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 422);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();

            $this->serviceRepository->update($id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật dịch vụ thành công!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    public function delete($id) {
        try {
            $this->serviceRepository->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Xóa dịch vụ thành công'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dịch vụ!'
            ], 404);
        }
    }
}