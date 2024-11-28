<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use Exception;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Repositories\Patient\PatientRepositoryInterface;
use App\Services\CloundinaryService;
use App\Repositories\UserInfo\UserInfoRepositoryInterface;
use Password;
use Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Mail\ForgotPassword;
use App\Models\User;
use App\Http\Resources\UserResourceThree;
use App\Models\PasswordReset;

class AuthService
{
    private $userRepository;
    private $patientInfoRepository;
    private $patientRepository;
    private $cloundinaryService;
    private $userInfoRepository;
    public function __construct(UserRepositoryInterface $userRepository, PatientInfoRepositoryInterface $patientInfoRepository, UserInfoRepositoryInterface $userInfoRepository, PatientRepositoryInterface $patientRepository, CloundinaryService $cloundinaryService)
    {
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
            $data['role_id'] = 'bdfe1b72-51c9-419f-b8af-453cbf4cc816';
            $token = Str::random(40);
            $data['token'] = $token;
            $user = $this->userRepository->create($data);
            $patient = $this->patientRepository->create(['user_id' => $user->id]);
            $data['patient_id'] = $patient->id;
            $this->patientInfoRepository->create($data);
            $url = url("/api/v1/verify-email/$token");
            Mail::to($data['email'])->send(new VerifyEmail($url, $data['email']));

            return response()->json(['success' => true, 'message' => 'Đăng ký tài khoản thành công, vui lòng kiểm tra mail để kích hoạt tài khoản!'], 201);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function login($request)
    {
        try {
            $credentials = $request->validated();

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['success' => false, 'message' => 'Thông tin đăng nhập của bạn không chính xác!'], 401);
            }

            $user = auth()->user();
            $status = [
                'inactive' => 'chưa được kích hoạt',
                'disabled' => 'đã bị vô hiệu hóa',
            ];
            if ($user->status == 'disabled' || $user->status == 'inactive') {
                return response()->json(['success' => false, 'message' => 'Tài khoản của bạn ' . $status[$user->status]], 403);
            }

            return $this->respondWithToken($token);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Token invalid!'], 401);
        }
    }

    public function profile()
    {
        try {
            return response()->json(['success' => true, 'data' => new UserResourceThree(auth()->user()->load('userInfo.identityCard', 'patient.patientInfo', 'patient.identityCard', 'role.permissions.actions'))], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập'], 401);
        }
    }

    public function updateProfile($request, $id)
    {
        try {
            $data = $request->all();
            $user = auth()->user()->load('role');

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

            return response()->json(['success' => true, 'message' => 'Cập nhật thông tin cá nhân thành công!', 'data' => new UserResource($user)], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 401);
        }
    }

    public function verifyEmail($token)
    {
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
                return response()->json(['success' => true, 'message' => 'Kích hoạt tài khoản thành công!'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
        }
    }

    public function changePassword($request)
    {
        try {
            $id = auth()->id();
            $data = $request->validated();

            $this->userRepository->update($id, ['password' => $data['new_password']]);

            return response()->json(['success' => true, 'message' => 'Cập nhật mật khẩu thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    public function forgotPsw($request)
    {
        try {
            $request->validate([
                'email' => 'required|exists:users,email'
            ], [
                'email.required' => 'Vui lòng nhập địa chỉ email!',
                'email.exists' => 'Địa chỉ email không tồn tại trong hệ thống!'
            ]);

            $email = $request->input('email');
            $otp = $this->generateUniqueOtp();
            $user = User::where('email', $email)->first();

            if ($user) {
                PasswordReset::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'expires_at' => now('Asia/Ho_Chi_Minh')->addMinutes(15),
                ]);
            }

            Mail::to($email)->send(new ForgotPassword($otp, $email));

            return response()->json(['success' => true, 'message' => 'Mã otp đã được gửi vào email của bạn, vui lòng check mail để khôi phục lại mật khẩu!']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    private function generateUniqueOtp()
    {
        do {
            $otp = $otp = rand(100000, 999999);
            $otpExists = PasswordReset::where('otp', $otp)->exists();
        } while ($otpExists);

        return $otp;
    }

    public function resetPsw($request)
    {
        try {
            $data = $request->validate([
                'otp' => 'required',
                'password' => 'required|min:8'
            ], [
                'required' => ':attribute là bắt buộc!',
                'min' => 'Mật khẩu phải lớn hơn 8 ký tự!'
            ]);

            $otp = $request->otp;
            $passwordReset = PasswordReset::with('user')
                ->where('otp', $otp)
                ->first();

            if (!$passwordReset) {
                return response()->json(['success' => false, 'message' => 'OTP không hợp lệ'], 400);
            } else if ($passwordReset->expires_at < now('Asia/Ho_Chi_Minh')) {
                PasswordReset::where('otp', $otp)->delete();
                return response()->json(['success' => false, 'message' => 'OTP đã hết hạn'], 400);
            }

            $passwordReset->user->update([
                'password' => $data['password']
            ]);

            PasswordReset::where('user_id', $passwordReset->user_id)->delete();

            return response()->json(['success' => true, 'message' => 'Mật khẩu của bạn đã được thay đổi!']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

    public function logout()
    {
        auth()->logout(true);

        return response()->json(['success' => true, 'message' => 'Tài khoản của bạn đã được đăng xuất!'], 200);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data' => new UserResource(auth()->user()->load('userInfo.identityCard', 'patient.patientInfo', 'patient.identityCard', 'role.permissions.actions'))
        ], 200);
    }
}
