<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Создаем 20 тестовых продуктов
        Product::factory()->count(20)->create();
    }
}