<?php
namespace App\Services;

use Exception;

use App\Repositories\User\UserRepositoryInterface;
use App\Services\CloundinaryService;
class AuthService {
    private $userRepository;
    private $image;
    public function __construct(UserRepositoryInterface $userRepository, CloundinaryService $image) {
        $this->userRepository = $userRepository;
        $this->image = $image;
    }

    public function create($request) {
        try {
            $data = $request->validated();

            $data['status'] = 'active';
            $data['role_id'] = 2;
            $this->userRepository->create($data);

            return response()->json(['message' => 'Register Successfully!'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function login($request) {
        try {
            $credentials = request(['email', 'password']);

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
            'data' => auth()->user()->load('role.type')
        ], 200);
    }
}