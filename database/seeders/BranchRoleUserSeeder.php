<?php

namespace Database\Seeders;

use App\Models\BranchRoleUser;
use Illuminate\Database\Seeder;

class BranchRoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BranchRoleUser::create([
            'user_id' => 1,
            'role_id' => 2,
            'assigned_by' => 1,
        ]);
        BranchRoleUser::create([
            'branch_id' => 1,
            'user_id' => 1,
            'role_id' => 3,
            'assigned_by' => 1,
        ]);
        BranchRoleUser::create([
            'user_id' => 1,
            'role_id' => 1,
            'assigned_by' => 1,
        ]);
    }
}
