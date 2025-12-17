<?php

namespace Database\Seeders;

use App\Models\ContactUser;
use Illuminate\Database\Seeder;

class ContactUserSeeder extends Seeder
{

    public function run(): void
    {
        ContactUser::create(['user_id'=>1, 'contact_id'=>1, 'is_primary'=>true]);
        ContactUser::create(['user_id'=>1, 'contact_id'=>2]);
    }
}
