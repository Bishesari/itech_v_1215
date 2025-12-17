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
            ['name_fa'=> 'البرز', 'name_en' => 'Alborz'],
            ['name_fa'=> 'اردبیل', 'name_en' => 'Ardabil'],
            ['name_fa'=> 'بوشهر', 'name_en' => 'Bushehr'],
            ['name_fa'=> 'چهارمحال و بختیاری', 'name_en' => 'Chaharmahal and Bakhtiari'],
            ['name_fa'=> 'آذربایجان شرقی', 'name_en' => 'East Azerbaijan'],
            ['name_fa'=> 'آذربایجان غربی', 'name_en' => 'West Azerbaijan'],
            ['name_fa'=> 'فارس', 'name_en' => 'Fars'],
            ['name_fa'=> 'گیلان', 'name_en' => 'Gilan'],
            ['name_fa'=> 'گلستان', 'name_en' => 'Golestan'],
            ['name_fa'=> 'همدان', 'name_en' => 'Hamadan'],
            ['name_fa'=> 'هرمزگان', 'name_en' => 'Hormozgan'],
            ['name_fa'=> 'ایلام', 'name_en' => 'Ilam'],
            ['name_fa'=> 'اصفهان', 'name_en' => 'Isfahan'],
            ['name_fa'=> 'کرمان', 'name_en' => 'Kerman'],
            ['name_fa'=> 'کرمانشاه', 'name_en' => 'Kermanshah'],
            ['name_fa'=> 'خوزستان', 'name_en' => 'Khuzestan'],
            ['name_fa'=> 'کهگلویه و بویراحمد', 'name_en' => 'Kohgiluyeh and Boyer-Ahmad'],
            ['name_fa'=> 'کردستان', 'name_en' => 'Kurdistan'],
            ['name_fa'=> 'لرستان', 'name_en' => 'Lorestan'],
            ['name_fa'=> 'مرکزی', 'name_en' => 'Markazi'],
            ['name_fa'=> 'مازندران', 'name_en' => 'Mazandaran'],
            ['name_fa'=> 'خراسان شمالی', 'name_en' => 'North Khorasan'],
            ['name_fa'=> 'خراسان رضوی', 'name_en' => 'Razavi Khorasan'],
            ['name_fa'=> 'خراسان جنوبی', 'name_en' => 'South Khorasan'],
            ['name_fa'=> 'قزوین', 'name_en' => 'Qazvin'],
            ['name_fa'=> 'قم', 'name_en' => 'Qom'],
            ['name_fa'=> 'سمنان', 'name_en' => 'Semnan'],
            ['name_fa'=> 'سیستان و بلوچستان', 'name_en' => 'Sistan and Baluchestan'],
            ['name_fa'=> 'تهران', 'name_en' => 'Tehran'],
            ['name_fa'=> 'یزد', 'name_en' => 'Yazd'],
            ['name_fa'=> 'زنجان', 'name_en' => 'Zanjan'],
        ];
        foreach ($provinces as $data) {
            Province::create($data);
        }
    }
}
