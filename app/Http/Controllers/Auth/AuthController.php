<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "phone", "address", "password"},
     *             @OA\Property(property="name", type="string", example="Иван Иванов"),
     *             @OA\Property(property="phone", type="string", example="+79991234567"),
     *             @OA\Property(property="address", type="string", example="ул. Примерная, д. 10"),
     *             @OA\Property(property="password", type="string", example="Secret1!", minLength=6),
     *             @OA\Property(property="password_confirmation", type="string", example="Secret1!", minLength=6)
     * 
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        return response()->json($user, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Authenticate user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone", "password"},
     *             @OA\Property(property="phone", type="string", example="+79991234567"),
     *             @OA\Property(property="password", type="string", example="Secret1!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login($request->validated());
        
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        return response()->json(['token' => $token]);
    }
}