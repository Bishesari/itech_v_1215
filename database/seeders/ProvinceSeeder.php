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
        ];
        foreach ($provinces as $data) {
            Province::create($data);
        }
    }
}
