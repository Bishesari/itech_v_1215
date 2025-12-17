<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['province_id'=> '3', 'name_fa' => 'بوشهر', 'name_en' => 'Bushehr'],
            ['province_id'=> '3', 'name_fa' => 'برازجان', 'name_en' => 'Borazjan'],
            ['province_id'=> '3', 'name_fa' => 'بندر گناوه', 'name_en' => 'Bandar Ganaveh'],
            ['province_id'=> '3', 'name_fa' => 'بندر کنگان', 'name_en' => 'Bandar Kangan'],
            ['province_id'=> '3', 'name_fa' => 'خورموج', 'name_en' => 'Khormoj'],
            ['province_id'=> '3', 'name_fa' => 'جم', 'name_en' => 'Jam'],
            ['province_id'=> '3', 'name_fa' => 'بندردیلم', 'name_en' => 'Bandar Deylam'],
            ['province_id'=> '3', 'name_fa' => 'بندردیر', 'name_en' => 'Bandar-e Deyr'],
            ['province_id'=> '3', 'name_fa' => 'علی‌شهر', 'name_en' => 'Ali Shahr'],
            ['province_id'=> '3', 'name_fa' => 'آب‌پخش', 'name_en' => 'Ab Pakhsh'],
            ['province_id'=> '3', 'name_fa' => 'نخل تقی', 'name_en' => 'Nakhl Taqi'],
            ['province_id'=> '3', 'name_fa' => 'چغادک', 'name_en' => 'Choghadak'],
            ['province_id'=> '3', 'name_fa' => 'اهرم', 'name_en' => 'Ahram'],
            ['province_id'=> '3', 'name_fa' => 'بانک', 'name_en' => 'Banak'],
            ['province_id'=> '3', 'name_fa' => 'عسلویه', 'name_en' => 'Asaluyeh'],
            ['province_id'=> '3', 'name_fa' => 'کاکی', 'name_en' => 'Kaki'],
            ['province_id'=> '3', 'name_fa' => 'وحدتیه', 'name_en' => 'Vahdatiyeh'],
            ['province_id'=> '3', 'name_fa' => 'سعدآباد', 'name_en' => 'Sadabad'],
            ['province_id'=> '3', 'name_fa' => 'خارگ', 'name_en' => 'Kharg'],
            ['province_id'=> '3', 'name_fa' => 'شبانکاره', 'name_en' => 'Shabankareh'],
            ['province_id'=> '3', 'name_fa' => 'بردستان', 'name_en' => 'Bardestan'],
            ['province_id'=> '3', 'name_fa' => 'بندر سیراف', 'name_en' => 'Bandar Siraf'],
            ['province_id'=> '3', 'name_fa' => 'آبدان', 'name_en' => 'Abdan'],
            ['province_id'=> '3', 'name_fa' => 'دالکی', 'name_en' => 'Dalaki'],
            ['province_id'=> '3', 'name_fa' => 'بندر ریگ', 'name_en' => 'Bandar Rig'],
            ['province_id'=> '3', 'name_fa' => 'بردخون', 'name_en' => 'Bord Khun'],
            ['province_id'=> '3', 'name_fa' => 'دوراهک', 'name_en' => 'Dowrahak'],
            ['province_id'=> '3', 'name_fa' => 'دلوار', 'name_en' => 'Delvar'],
            ['province_id'=> '3', 'name_fa' => 'بادوله', 'name_en' => 'Baduleh'],
            ['province_id'=> '3', 'name_fa' => 'آناهستان', 'name_en' => 'Anarestan'],
            ['province_id'=> '3', 'name_fa' => 'ریز', 'name_en' => 'Riz'],
            ['province_id'=> '3', 'name_fa' => 'تنگ ارم', 'name_en' => 'Tang-e Eram'],
        ];

        foreach ($cities as $data) {
            City::create($data);
        }

        $cities = [
            ['province_id'=> '1', 'name_fa' => 'کرج',         'name_en' => 'Karaj'],
            ['province_id'=> '1', 'name_fa' => 'فردیس',       'name_en' => 'Fardis'],
            ['province_id'=> '1', 'name_fa' => 'نظرآباد',     'name_en' => 'Nazarabad'],
            ['province_id'=> '1', 'name_fa' => 'هشتگرد',      'name_en' => 'Hashtgerd'],
            ['province_id'=> '1', 'name_fa' => 'محمدشهر',     'name_en' => 'Mohammadshahr'],
            ['province_id'=> '1', 'name_fa' => 'ماهدشت',      'name_en' => 'Mahdasht'],
            ['province_id'=> '1', 'name_fa' => 'کمال‌شهر',    'name_en' => 'Kamal Shahr'],
            ['province_id'=> '1', 'name_fa' => 'گرمدره',      'name_en' => 'Garmdareh'],
            ['province_id'=> '1', 'name_fa' => 'مشکین‌دشت',   'name_en' => 'Meshkin Dasht'],
            ['province_id'=> '1', 'name_fa' => 'طالقان',      'name_en' => 'Taleqan'],
            ['province_id'=> '1', 'name_fa' => 'تنکمان',      'name_en' => 'Tankaman'],
            ['province_id'=> '1', 'name_fa' => 'کوهسار',      'name_en' => 'Koohsar'],
        ];
        foreach ($cities as $data) {
            City::create($data);
        }
    }
}
