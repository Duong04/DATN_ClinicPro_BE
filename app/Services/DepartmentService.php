<?php
namespace App\Services;

use App\Http\Resources\DepartmentResource;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\UserInfo\UserInfoRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
class DepartmentService {
    private $departmentRepository;
    private $userInfoRepository;
    private $userRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository, UserInfoRepositoryInterface $userInfoRepository, UserRepositoryInterface $userRepository) {
        $this->departmentRepository = $departmentRepository;
        $this->userInfoRepository = $userInfoRepository;
        $this->userRepository = $userRepository;
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $departments = $this->departmentRepository->paginate($limit, $q);
            
            if ($limit) {
                return response()->json([
                    'data' => DepartmentResource::collection($departments->items()),
                    'prev_page_url' => $departments->previousPageUrl(),
                    'next_page_url' => $departments->nextPageUrl(),
                    'total' => $departments->total()
                ], 200);
            }
            return response()->json(['data' => DepartmentResource::collection($departments)], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();
            $department = '';

            if (isset($data['users'])) {
                foreach ($data['users'] as $user) {
                    $existingUser = $this->userRepository->find($user, ['userInfo']);

                    if ($existingUser && $existingUser->userInfo->department_id) {
                        return response()->json([
                            'message' => "Người dùng ID $user đã thuộc phòng ban khác!"
                        ], 422);
                    }
                    $department = $this->departmentRepository->create($data);
                    $this->userInfoRepository->update($user, [
                        'department_id' => $department->id
                    ]);
                }
            }else {
                $department = $this->departmentRepository->create($data);
            }

            return response()->json([
                'message' => 'Tạo phòng ban thành công!',
                'data' => $department
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id) {
        try {

            $checkExist = $this->departmentRepository->find($id);

            if (empty($checkExist)) {
                return response()->json(['message' => 'Không tìm thấy dữ liệu phòng ban!'], 404);
            }

            $data = $request->validated();

            if (isset($data['users'])) {
                foreach ($data['users'] as $user) {
                    $existingUser = $this->userRepository->find($user, ['userInfo']);

                    if ($existingUser && $existingUser->userInfo->department_id && $existingUser->userInfo->department_id != $id) {
                        return response()->json([
                            'message' => "Người dùng ID $user đã thuộc phòng ban khác!"
                        ], 422);
                    }
                    $department = $this->departmentRepository->update($id, $data);

                    $this->userInfoRepository->update($user, [
                        'department_id' => $id
                    ]);
                }
            }

            if (isset($data['users_delete'])) {
                foreach ($data['users_delete'] as $user) {
                    $existingUser = $this->userRepository->find($user, ['userInfo']);

                    $this->userInfoRepository->update($user, [
                        'department_id' => null
                    ]);
                }
            }

            return response()->json(['message' => 'Cập nhât phòng ban thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    public function findById($id) {
        try {
            $department = $this->departmentRepository->find($id);

            if (empty($department)) {
                return response()->json(['message' => 'Không tìm thấy phòng ban!'], 404);
            }

            return response()->json(['data' => new DepartmentResource($department)], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function delete($id) {
        try {
            $check_role = $this->departmentRepository->find($id);
            if ($check_role->users_count > 0) {
                return response()->json(['message' => 'Phòng ban này đã được gán cho người dùng không thể xóa được!'], 400);
            } 

            $this->departmentRepository->delete($id);

            return response()->json(['data' => 'Đã xóa phòng ban thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Không tìm thấy phòng ban!'], 404);
        }
    }
}