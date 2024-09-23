<?php
namespace App\Services;

use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\PermissionAction\PermissionActionRepositoryInterface;

class PermissionService {
    private $permissionRepository;
    private $permissionActionRepository;
    public function __construct(PermissionRepositoryInterface $permissionRepository, PermissionActionRepositoryInterface $permissionActionRepository) {
        $this->permissionRepository = $permissionRepository;
        $this->permissionActionRepository = $permissionActionRepository;
    }

    public function getAll() {
        try {
            $actions = $this->permissionRepository->all();

            return response()->json(['data' => $actions], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $permissions = $this->permissionRepository->paginate($limit, $q);
    
            if ($limit) {
                return response()->json([
                    'data' => $permissions->items(),
                    'prev_page_url' => $permissions->previousPageUrl(),
                    'next_page_url' => $permissions->nextPageUrl(),
                    'total' => $permissions->total()
                ], 200);
            }
    
            return response()->json(['data' => $permissions], 200);
    
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
    

    public function findById($id) {
        try {
            $permission = $this->permissionRepository->find($id);
            if (empty($permission)) {
                return response()->json(['error' => 'Permission not found!'], 404);
            }

            return response()->json(['data' => $permission], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();
            $permission = $this->permissionRepository->create($data);
            if (isset($data['actions'])) {
                foreach ($data['actions'] as $item) {
                    $this->permissionActionRepository->create([
                        'permission_id' => $permission->id,
                        'action_id' => $item['action_id'],
                    ]);
                }
            }

            return response()->json(['message' => 'Created permission successfully!', 'data' => $permission], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();
            $this->permissionActionRepository->delete('permission_id', $id);
            if (isset($data['actions'])) {
                $permission = $this->permissionRepository->update($id, $data);
                foreach ($data['actions'] as $item) {
                    $this->permissionActionRepository->create([
                        'permission_id' => $id,
                        'action_id' => $item['action_id'],
                    ]);
                }
            }

            return response()->json(['message' => 'Updated permission successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function delete($id) {
        try {

            $this->permissionRepository->delete($id);

            return response()->json(['message' => 'Deleted action successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Action not found!'], 404);
        }
    }
}