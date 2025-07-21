<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     required={"id", "name", "price"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Beef"),
 *     @OA\Property(property="description", type="string", example="Premium quality beef"),
 *     @OA\Property(property="price", type="number", format="float", example=9.99),
 *     @OA\Property(property="category", type="string", example="meat"),
 *     @OA\Property(property="stock", type="integer", example=100)
 * )
 */
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}