<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use Exception;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Repositories\Patient\PatientRepositoryInterface;
use App\Services\CloundinaryService;
use App\Repositories\UserInfo\UserInfoRepositoryInterface;
use Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Models\User;

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

            $data['status'] = 'inactive';
            $data['role_id'] = 2;
            $token = Str::random(40);
            $data['token'] = $token;
            $user = $this->userRepository->create($data);
            $patient = $this->patientRepository->create(['user_id' => $user->id]);
            $data['patient_id'] = $patient->id;
            $this->patientInfoRepository->create($data);
            $url = url("/api/v1/verify-email/$token");
            Mail::to($data['email'])->send(new VerifyEmail($url, $data['email']));

            return response()->json(['message' => 'Đăng ký tài khoản thành công!'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function login($request)
    {
        try {
            $credentials = $request->validated();

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Thông tin đăng nhập của bạn không chính xác!'], 401);
            }

            $user = auth()->user();
            if ($user->status == 'disabled' || $user->status == 'inactive') {
                return response()->json(['error' => 'Your account is ' . $user->status], 403);
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
            return response()->json(['data' => new UserResource(auth()->user()->load('userInfo.identityCard', 'patient.patientInfo', 'patient.identityCard','role.permissions.actions'))], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Bạn không có quyền truy cập'], 401);
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

            return response()->json(['message' => 'Cập nhật thông tin cá nhân thành công!', 'data' => new UserResource($user)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 401);
        }
    }

    public function verifyEmail($token) {
        try {
            $checkToken = User::where('token', $token)->first();
            if (empty($checkToken)) {
                return response()->json(['error' => 'Token không hợp lệ!']);
            }

            $updateStatus = $checkToken->update([
                'status' => 'active',
                'token' => null
            ]);

            if ($updateStatus) {
                return response()->json(['message' => 'Kích hoạt tài khoản thành công!'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function logout()
    {
        auth()->logout(true);

        return response()->json(['message' => 'Tài khoản của bạn đã được đăng xuất!'], 200);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data' => new UserResource(auth()->user()->load('userInfo.identityCard', 'patient.patientInfo', 'patient.identityCard','role.permissions.actions'))
        ], 200);
    }
}
