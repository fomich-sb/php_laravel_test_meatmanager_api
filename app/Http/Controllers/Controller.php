<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Meatproduction API",
 *     version="1.0.0",
 *     description="API for Meatproduction orders management"
 * )
 * 
 * @OA\Schema(
 *     schema="Pagination",
 *     type="object",
 *     @OA\Property(property="current_page", type="integer"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(type="object")
 *     ),
 *     @OA\Property(property="first_page_url", type="string"),
 *     @OA\Property(property="from", type="integer"),
 *     @OA\Property(property="last_page", type="integer"),
 *     @OA\Property(property="last_page_url", type="string"),
 *     @OA\Property(
 *         property="links",
 *         type="array",
 *         @OA\Items(type="object")
 *     ),
 *     @OA\Property(property="next_page_url", type="string"),
 *     @OA\Property(property="path", type="string"),
 *     @OA\Property(property="per_page", type="integer"),
 *     @OA\Property(property="prev_page_url", type="string"),
 *     @OA\Property(property="to", type="integer"),
 *     @OA\Property(property="total", type="integer")
 * )
 * 
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Error message"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={"field": {"Error message"}}
 *     )
 * )
 */
abstract class Controller
{
    //
}
