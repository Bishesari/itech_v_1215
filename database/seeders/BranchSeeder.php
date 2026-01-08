<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'code' => 'BUS-001',
            'short_name' => 'آی تک',
            'full_name' => 'آموزشگاه فنی و حرفه ای آزاد آی تک',
            'abbr' => 'ITC',
            'province_id' => 1,
            'city_id' => 1,
            'address' => 'خیابان سنگی، ابتدای کوچه گلخانه، نبش بهشت1، ساختمان سیراف5، طبقه اول، واحد 3',
            'postal_code' => '7514977863',
            'phone' => '07733543850',
            'mobile' => '09350568163',
            'credit_balance' => 100000000,
        ]);
        Branch::create([
            'code' => 'BUS-002',
            'short_name' => 'آی کد',
            'full_name' => 'آموزشگاه نخبگان آی کد',
            'abbr' => 'ICC',
            'province_id' => 1,
            'city_id' => 1,
            'address' => 'خیابان سنگی، ابتدای کوچه گلخانه، نبش بهشت1، ساختمان سیراف5، طبقه اول، واحد 3',
            'postal_code' => '7514977863',
            'phone' => '07733543850',
            'mobile' => '09350568163',
            'credit_balance' => 100000000,
        ]);
    }
}
