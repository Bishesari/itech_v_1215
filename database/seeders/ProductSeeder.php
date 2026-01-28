<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Products\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => Category::where('slug', 'written-questions')->first()->id,
            'standard_id' => 2,
            'title' => 'نمونه سوالات پر تکرار نهایی پایتون',
            'price' => 500_000,
        ]);

        Product::create([
            'category_id' => Category::where('slug', 'written-questions')->first()->id,
            'standard_id' => 1,
            'title' => 'نمونه سوالات پر تکرار نهایی لاراول',
            'price' => 500_000,
        ]);
    }
}
