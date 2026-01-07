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
        $this->call(ProvinceSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(FieldSeeder::class);

        /*
        
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
    */
    }
}
