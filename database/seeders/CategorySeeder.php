<?php

namespace Database\Seeders;

use App\Models\Products\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['title' => 'نمونه سوالات کتبی', 'slug' => 'written-questions'],
            ['title' => 'نمونه سوالات عملی', 'slug' => 'practical-questions'],
            ['title' => 'آزمون شبیه‌سازی شده', 'slug' => 'mock-exams', 'is_repeatable' => true],
            ['title' => 'دوره حضوری عمومی', 'slug' => 'public-onsite'],
            ['title' => 'دوره حضوری خصوصی', 'slug' => 'private-onsite'],
            ['title' => 'دوره آنلاین عمومی', 'slug' => 'public-online'],
            ['title' => 'دوره آنلاین خصوصی', 'slug' => 'private-online'],
            ['title' => 'خدمات فنی', 'slug' => 'technical-services', 'is_repeatable' => true],
            ['title' => 'خدمات جانبی', 'slug' => 'addons', 'is_repeatable' => true],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }
    }
}
