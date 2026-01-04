<?php

namespace Database\Seeders;

use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chapters = [
            ['standard_id' => '1', 'number' => '1', 'title' => 'نصب و راه اندازی Laravel'],
            ['standard_id' => '1', 'number' => '2', 'title' => 'ایجاد و ساخت مدل، نما، کنترلر و مسیريابی'],
            ['standard_id' => '1', 'number' => '3', 'title' => 'ایجاد و ساخت Migration'],
            ['standard_id' => '1', 'number' => '4', 'title' => 'قالب سازی با Blade در فریمورک لاراول'],
            ['standard_id' => '1', 'number' => '5', 'title' => 'ایجاد کردن نگاشت رابطه به شی'],
            ['standard_id' => '1', 'number' => '6', 'title' => 'ایجاد و ساخت کوئری با Query Builder'],
            ['standard_id' => '1', 'number' => '7', 'title' => ' اعتبارسنجی فرم ها در لاراول '],
        ];
        foreach ($chapters as $data) {
            Chapter::create($data);
        }
    }
}
