<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['name_fa' => 'بوشهر', 'name_en' => 'Bushehr'],
            ['name_fa' => 'تهران', 'name_en' => 'Tehran'],
            ['name_fa' => 'اصفهان', 'name_en' => 'Isfahan'],
            ['name_fa' => 'فارس', 'name_en' => 'Fars'],
            ['name_fa' => 'خوزستان', 'name_en' => 'Khoozestan'],
        ];
        foreach ($provinces as $data) {
            Province::create($data);
        }
    }
}
