<?php

namespace Database\Seeders;

use App\Models\Standard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Standard::create(['field_id' => 1, 'code' => '251340530000031', 'name_fa' => 'ساخت برنامه های وب با استفاده از Laravel Framework',
            'name_en' => 'Build Web Apps Using Laravel Framework', 'nazari_h' => 30, 'amali_h' => 60, 'karvarzi_h' => 0,
            'project_h' => 0, 'sum_h' => 90, 'required_h'=>120]);
    }
}
