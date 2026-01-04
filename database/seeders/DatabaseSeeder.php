<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            ContactSeeder::class,
            ContactUserSeeder::class,
            RoleSeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            BranchSeeder::class,
            BranchRoleUserSeeder::class,
            FieldSeeder::class,
            StandardSeeder::class,
            ChapterSeeder::class,
        ]);
    }
}
