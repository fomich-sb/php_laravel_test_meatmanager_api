<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OrderController extends Controller implements HasMiddleware
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public static function middleware(): array
    {
        return [
            'auth:api'
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create new order",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "items"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     required={"product_id", "quantity"},
     *                     @OA\Property(property="product_id", type="integer", example=5),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             ),
     *             @OA\Property(property="comment", type="string", example="Доставить после 18:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->validated());
        return response()->json(new OrderResource($order), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Get user orders",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of orders",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Order")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $userId = request()->query('user_id');
        $orders = $this->orderService->getUserOrders($userId);
        return response()->json(OrderResource::collection($orders));
    }
}