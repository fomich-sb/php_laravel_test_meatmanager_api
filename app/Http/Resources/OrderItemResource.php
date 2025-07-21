<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     type="object",
 *     title="OrderItem",
 *     required={"id", "order_id", "product_id", "quantity", "price"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="order_id", type="integer", example=1),
 *     @OA\Property(property="product_id", type="integer", example=5),
 *     @OA\Property(property="quantity", type="integer", example=2),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(
 *         property="product",
 *         ref="#/components/schemas/Product"
 *     )
 * )
 */
class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'product' => new ProductResource($this->whenLoaded('product'))
        ];
    }
}