<?php
namespace App\Services;

use App\Http\Resources\RoleResource;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\RolePermission\RolePermissionRepositoryInterface;

class RoleService {
    private $roleRepository;
    private $rolePermissionRepository;
    
    public function __construct(RoleRepositoryInterface $roleRepository, RolePermissionRepositoryInterface $rolePermissionRepository) {
        $this->roleRepository = $roleRepository;
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $roles = $this->roleRepository->paginate($limit, $q);

            if ($limit) {
                return response()->json([
                    'data' => RoleResource::collection($roles->items()),
                    'prev_page_url' => $roles->previousPageUrl(),
                    'next_page_url' => $roles->nextPageUrl(),
                    'total' => $roles->total()
                ], 200);
            }
            return response()->json(['success' => true, 'data' => RoleResource::collection($roles)], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
        }
    }

    public function findById($id) {
        try {
            $role = $this->roleRepository->find($id);
            if (empty($role)) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy vai trò!'], 404);
            }

            $role = new RoleResource($role);
            return  response()->json(['success' => true, 'data' => $role]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();
            $role = $this->roleRepository->create($data);
            if (isset($data['permissions'])) {
                foreach ($data['permissions'] as $permission) {
                    foreach ($permission['actions'] as $action) {
                        $this->rolePermissionRepository->create([
                            'role_id' => $role->id,
                            'permission_id' => $permission['id'],
                            'action_id' => $action['id'],
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Tạo vai trò thành công!', 'data' => $role], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();
            $role = $this->roleRepository->update($id, $data);
            if (isset($data['permissions'])) {
                $this->rolePermissionRepository->delete($id);
                foreach ($data['permissions'] as $permission) {
                    foreach ($permission['actions'] as $action) {
                        $this->rolePermissionRepository->create([
                            'role_id' => $id,
                            'permission_id' => $permission['id'],
                            'action_id' => $action['id'],
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Cập nhật vai trò thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    public function delete($id) {
        try {
            $check_role = $this->roleRepository->find($id);
            if ($check_role->users_count > 0) {
                return response()->json(['success' => false, 'message' => 'Vai trò này đã gán cho người dùng không thể xóa được!'], 400);
            } 

            $this->roleRepository->delete($id);
            return response()->json(['success' => true, 'message' => 'Đã xóa thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy vai trò!'], 404);
        }
    }


}