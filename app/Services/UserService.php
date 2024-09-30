<?php
namespace App\Services;

use App\Http\Resources\UserResourceTwo;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserInfo\UserInfoRepositoryInterface;
use App\Models\IdentityCard;
use App\Services\CloundinaryService;
use App\Models\Doctor;

class UserService {
    private $userRepository;
    private $userInfoRepository;
    private $cloundinaryService;

    public function __construct(UserRepositoryInterface $userRepository, UserInfoRepositoryInterface $userInfoRepository, CloundinaryService $cloundinaryService) {
        $this->userRepository = $userRepository;
        $this->userInfoRepository = $userInfoRepository;
        $this->cloundinaryService = $cloundinaryService;
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $users = $this->userRepository->paginate($limit, $q);
            
            if ($limit) {
                return response()->json([
                    'data' => UserResourceTwo::collection($users->items()),
                    'prev_page_url' => $users->previousPageUrl(),
                    'next_page_url' => $users->nextPageUrl(),
                    'total' => $users->total()
                ], 200);
            }
            return response()->json(['data' => UserResourceTwo::collection($users)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function getByRoleId($request, $role_id) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $users = $this->userRepository->getByRoleId($role_id, $limit, $q);
            
            if ($limit) {
                return response()->json([
                    'data' => UserResourceTwo::collection($users->items()),
                    'prev_page_url' => $users->previousPageUrl(),
                    'next_page_url' => $users->nextPageUrl(),
                    'total' => $users->total()
                ], 200);
            }
            return response()->json(['data' => UserResourceTwo::collection($users)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();
    
            $user = $this->userRepository->create($data);
    
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $folder = 'avatars';
    
                $data['user_info']['avatar'] = $this->cloundinaryService->upload($file, $folder);
            }
    
            if ($data['role_id'] == 1) {
                if (isset($data['doctor'])) {
                    $data['doctor']['user_id'] = $user->id;
                    Doctor::create($data['doctor']);
                }
            }

            $identityCard = null;
            if (isset($data['user_info']['identity_card'])) {
                $identityCard = IdentityCard::create($data['user_info']['identity_card']);
            }
    
            if (isset($data['user_info'])) {
                if ($identityCard) {
                    $data['user_info']['identity_card_id'] = $identityCard->id;
                }
                
                $data['user_info']['user_id'] = $user->id;
                $this->userInfoRepository->create($data['user_info']);
            }
    
            $user = $this->userRepository->find($user->id, ['userInfo.identityCard']);
    
            return response()->json([
                'message' => 'Tạo tài khoản thành công!',
                'data' => $user
            ], 201);
    
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();

            $this->userRepository->update($id, $data);
            $user = $this->userRepository->find($id, ['userInfo.identityCard']);


            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $folder = 'avatars';
    
                $data['user_info']['avatar'] = $this->cloundinaryService->upload($file, $folder);
            }

            if (isset($data['role_id']) && $data['role_id'] == 1) {
                if (isset($data['doctor'])) {
                    $checkData = Doctor::where('user_id', $id)->first();
                    if (empty($checkData)) {
                        $data['doctor']['user_id'] = $id;
                        Doctor::create($data['doctor']);
                    }else {
                        $checkData->update($data['doctor']);
                    }
                }
            }
    
            if (isset($data['user_info']['identity_card'])) {
                if (!$user->userInfo->identityCard) {
                    $identityCard = IdentityCard::create($data['user_info']['identity_card']);
                    $data['user_info']['identity_card_id'] = $identityCard->id;
                } else {
                    IdentityCard::where('id', $user->userInfo->identityCard->id)
                        ->update($data['user_info']['identity_card']);
                }

                unset($data['user_info']['identity_card']);
            }
    
            if (isset($data['user_info'])) {
                $data['user_info']['user_id'] = $user->id;
                $this->userInfoRepository->update($id, $data['user_info']);
            }
    
            $user = $this->userRepository->find($user->id, ['userInfo.identityCard']);
            return response()->json(['message' => 'Cập nhật tài khoản thành công'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function findById($id) {
        try {
            $user = $this->userRepository->find( $id, ['role', 'userInfo.identityCard', 'patient.patientInfo', 'patient.identityCard']);

            if (empty($user)) {
                return response()->json(['error' => 'Không tìm thấy người dùng!'], 404);
            }

            return response()->json(['data' => new UserResourceTwo($user)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
}