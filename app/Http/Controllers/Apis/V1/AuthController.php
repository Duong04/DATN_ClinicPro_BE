<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangePswRequest;
use Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private $authService;
    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request) {
        return $this->authService->create($request);
    }

    public function login(LoginRequest $request) {
        return $this->authService->login($request);
    }

    public function updateProfile(ProfileRequest $request, $id) {
        return $this->authService->updateProfile($request, $id);
    }

    public function refresh() {
        return $this->authService->refresh();
    }

    public function changePassword(ChangePswRequest $request) {
        return $this->authService->changePassword($request);
    }

    public function profile() {
        return $this->authService->profile();
    }

    public function logout() {
        return $this->authService->logout();
    }

    public function verifyEmail($token) {
        return $this->authService->verifyEmail($token);
    }

    public function forgotPsw(Request $request) {
        return $this->authService-> forgotPsw($request);
    }

    public function redirectResetPsw($otp) {
        return response()->json($otp);
    }

    public function resetPsw(Request $request) {
        return $this->authService->resetPsw($request);
    }
}
