<?php
namespace App\Services;

use App\Http\Resources\DepartmentResource;
use App\Repositories\Department\DepartmentRepositoryInterface;

class DepartmentService {
    private $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository) {
        $this->departmentRepository = $departmentRepository;
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $departments = $this->departmentRepository->paginate($limit, $q);
            
            if ($limit) {
                return response()->json([
                    'data' => $departments->items(),
                    'prev_page_url' => $departments->previousPageUrl(),
                    'next_page_url' => $departments->nextPageUrl(),
                    'total' => $departments->total()
                ], 200);
            }
            return response()->json(['data' => $departments], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();
            $department = $this->departmentRepository->create($data);

            return response()->json([
                'message' => 'Tạo phòng ban thành công!',
                'data' => $department
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();

            $department = $this->departmentRepository->update($id, $data);
            return response()->json(['message' => 'Cập nhât phòng ban thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function findById($id) {
        try {
            $department = $this->departmentRepository->find($id);

            if (empty($department)) {
                return response()->json(['error' => 'Không tìm thấy phòng ban!'], 404);
            }

            return response()->json(['data' => new DepartmentResource($department)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function delete($id) {
        try {
            $check_role = $this->departmentRepository->find($id);
            if ($check_role->users_count > 0) {
                return response()->json(['error' => 'Phòng ban này đã được gán cho người dùng không thể xóa được!'], 400);
            } 

            $this->departmentRepository->delete($id);

            return response()->json(['data' => 'Đã xóa phòng ban thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Không tìm thấy phòng ban!'], 404);
        }
    }
}