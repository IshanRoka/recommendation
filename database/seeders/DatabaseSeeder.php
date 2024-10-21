<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Rating;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Users
        User::factory(5)->create();

        // Create Products
        Product::factory(10)->create();

        // Generate Ratings
        foreach (range(1, 5) as $userId) {
            foreach (range(1, 10) as $productId) {
                Rating::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'rating' => rand(1, 5),
                ]);
            }
        }
    }
}