<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use Exception;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Repositories\Patient\PatientRepositoryInterface;

class AuthService {
    private $userRepository;
    private $patientInfoRepository;
    private $patientRepository;
    public function __construct(UserRepositoryInterface $userRepository, PatientInfoRepositoryInterface $patientInfoRepository, PatientRepositoryInterface $patientRepository) {
        $this->userRepository = $userRepository;
        $this->patientInfoRepository = $patientInfoRepository;
        $this->patientRepository = $patientRepository;
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
            return response()->json(['data' => auth()->user()], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
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
