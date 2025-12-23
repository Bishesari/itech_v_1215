<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            // بوشهر
            'Bushehr' => [
                ['بندر بوشهر', 'Bandar-Bushehr'],
            ]
        ];

        foreach ($cities as $provinceEn => $cityList) {
            $province = Province::where('name_en', $provinceEn)->first();

            if (!$province) continue;

            foreach ($cityList as [$fa, $en]) {
                City::firstOrCreate([
                    'name_fa' => $fa,
                    'name_en' => $en,
                    'province_id' => $province->id,
                ]);
            }
        }
    }
}
