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
            'name' => 'مرکزی بوشهر',
            'code' => 'BUS-001',
            'province_id' => 1,
            'city_id' => 1,
            'address' => 'خیابان سنگی، ابتدای کوچه گلخانه، نبش بهشت1، ساختمان سیراف5، طبقه اول، واحد 3',
            'postal_code' => '7514977863',
            'phone' => '07733543850',
            'mobile' => '09350568163',
            'remain_credit' => 100000000,
        ]);
    }
}
