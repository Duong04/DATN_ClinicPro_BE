<?php
namespace App\Services;

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
                'message' => 'Created department successfully!',
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
            return response()->json(['message' => 'Updated department successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function findById($id) {
        try {
            $department = $this->departmentRepository->find($id);

            if (empty($department)) {
                return response()->json(['error' => 'Department not found!'], 404);
            }

            return response()->json(['data' => $department], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function delete($id) {
        try {
            $check_role = $this->departmentRepository->find($id);
            if ($check_role->users_count > 0) {
                return response()->json(['error' => 'Department has users assigned!'], 400);
            } 

            $this->departmentRepository->delete($id);

            return response()->json(['data' => 'Deleted department successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Department not found!'], 404);
        }
    }
}