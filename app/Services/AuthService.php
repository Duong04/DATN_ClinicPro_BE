<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use Exception;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Repositories\Patient\PatientRepositoryInterface;
use App\Services\CloundinaryService;
use App\Repositories\UserInfo\UserInfoRepositoryInterface;


class AuthService {
    private $userRepository;
    private $patientInfoRepository;
    private $patientRepository;
    private $cloundinaryService;
    private $userInfoRepository;
    public function __construct(UserRepositoryInterface $userRepository, PatientInfoRepositoryInterface $patientInfoRepository, UserInfoRepositoryInterface $userInfoRepository, PatientRepositoryInterface $patientRepository, CloundinaryService $cloundinaryService) {
        $this->userRepository = $userRepository;
        $this->patientInfoRepository = $patientInfoRepository;
        $this->userInfoRepository = $userInfoRepository;
        $this->patientRepository = $patientRepository;
        $this->cloundinaryService = $cloundinaryService;
    }

    public function create($request)
    {
        try {
            $data = $request->validated();

            $data['status'] = 'active';
            $data['role_id'] = 2;
            $user = $this->userRepository->create($data);
            $patient = $this->patientRepository->create(['user_id' => $user->id]);
            $data['patient_id'] = $patient->id;
            $this->patientInfoRepository->create($data);

            return response()->json(['message' => 'Register Successfully!'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function login($request)
    {
        try {
            $credentials = $request->validated();

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        } catch (Exception $e) {
            return response()->json(['error' => 'Token invalid!'], 401);
        }
    }

    public function profile()
    {
        try {
            return response()->json(['data' => new UserResource(auth()->user()->load('userInfo', 'patient.patientInfo', 'role.permissions.actions'))], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function updateProfile($request, $id) {
        try {
            $data = $request->all();
            $user = auth()->user()->load('role');

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $folder = 'avatars';
                $url = $this->cloundinaryService->upload($file, $folder);
                $data['user_info']['avatar'] = $url;
            }

            if (isset($data['email'])) {
                $data['user_info']['email'] = $data['email'];
            }

            if ($user->role->name == 'patient') {
                $user = $user->load('patient.patientInfo');
                $patientInfo = $user->patient->patientInfo;
                if (isset($data['user_info'])) {
                    $patientInfo->update($data['user_info']);
                }
            } else {
                $user = $user->load('userInfo');
                $userInfo = $user->userInfo;
                if (isset($data['user_info'])) {
                    $userInfo->update($data['user_info']);
                }
            }
    
            $user->update($data);

            return response()->json(['message' => 'Updated profile successfully!', 'data' => new UserResource($user)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 401);
        }
    }

    public function logout()
    {
        auth()->logout(true);

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data' => new UserResource(auth()->user()->load('userInfo', 'patient.patientInfo', 'role.permissions.actions'))
        ], 200);
    }
}
