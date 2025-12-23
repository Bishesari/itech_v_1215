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
            // تهران
            'Tehran' => [
                ['تهران', 'Tehran'],
                ['شهریار', 'Shahriar'],
                ['ری', 'Ray'],
                ['اسلامشهر', 'Eslamshahr'],
            ],

            // اصفهان
            'Isfahan' => [
                ['اصفهان', 'Isfahan'],
                ['کاشان', 'Kashan'],
                ['نجف‌آباد', 'Najafabad'],
            ],

            // فارس
            'Fars' => [
                ['شیراز', 'Shiraz'],
                ['مرودشت', 'Marvdasht'],
                ['جهرم', 'Jahrom'],
            ],

            // بوشهر
            'Bushehr' => [
                ['بوشهر', 'Bushehr'],
                ['برازجان', 'Borazjan'],
                ['بندر گناوه', 'Bandar Ganaveh'],
                ['بندر کنگان', 'Bandar Kangan'],
                ['خورموج', 'Khormoj'],
                ['جم', 'Jam'],
                ['بندردیلم', 'Bandar Deylam'],
                ['بندردیر', 'Bandar-e Deyr'],
                ['علی‌شهر', 'Ali Shahr'],
                ['آب‌پخش', 'Ab Pakhsh'],
                ['نخل تقی', 'Nakhl Taqi'],
                ['چغادک', 'Choghadak'],
                ['اهرم', 'Ahram'],
                ['بانک', 'Banak'],
                ['عسلویه', 'Asaluyeh'],
                ['کاکی', 'Kaki'],
                ['وحدتیه', 'Vahdatiyeh'],
                ['سعدآباد', 'Sadabad'],
                ['خارگ', 'Kharg'],
                ['شبانکاره', 'Shabankareh'],
                ['بردستان', 'Bardestan'],
                ['بندر سیراف', 'Bandar Siraf'],
                ['آبدان', 'Abdan'],
                ['دالکی', 'Dalaki'],
                ['بندر ریگ', 'Bandar Rig'],
                ['بردخون', 'Bord Khun'],
                ['دوراهک', 'Dowrahak'],
                ['دلوار', 'Delvar'],
                ['بادوله', 'Baduleh'],
                ['آناهستان', 'Anarestan'],
                ['ریز', 'Riz'],
                ['تنگ ارم', 'Tang-e Eram'],
            ],


            // البرز
            'Alborz' => [
                ['کرج', 'Karaj'],
                ['فردیس', 'Fardis'],
                ['نظرآباد', 'Nazarabad'],
                ['هشتگرد', 'Hashtgerd'],
                ['محمدشهر', 'Mohammadshahr'],
                ['ماهدشت', 'Mahdasht'],
                ['کمال‌شهر', 'Kamal Shahr'],
                ['گرمدره', 'Garmdareh'],
                ['مشکین‌دشت', 'Meshkin Dasht'],
                ['طالقان', 'Taleqan'],
                ['تنکمان', 'Tankaman'],
                ['کوهسار', 'Koohsar'],
            ],
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
