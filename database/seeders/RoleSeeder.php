<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name_en' => 'Newbie', 'name_fa' => 'تازه وارد'],
            ['name_en' => 'SuperAdmin', 'name_fa' => 'سوپر ادمین'],
            ['name_en' => 'Founder', 'name_fa' => 'موسس'],
            ['name_en' => 'Manager', 'name_fa' => 'مدیر'],
            ['name_en' => 'Assistant', 'name_fa' => 'مسئول اداری'],
            ['name_en' => 'Accountant', 'name_fa' => 'حسابدار'],
            ['name_en' => 'Teacher', 'name_fa' => 'مربی'],
            ['name_en' => 'Student', 'name_fa' => 'کارآموز'],
            ['name_en' => 'QuestionMaker', 'name_fa' => 'طراح سوال'],
            ['name_en' => 'QuestionAuditor', 'name_fa' => 'ممیز سوال'],
            ['name_en' => 'Examiner', 'name_fa' => 'آزمونگر'],
            ['name_en' => 'Marketer', 'name_fa' => 'بازاریاب'],
            ['name_en' => 'JobSeeker', 'name_fa' => 'کارجو'],
            ['name_en' => 'Examinee', 'name_fa' => 'آزمون دهنده'],
            ['name_en' => 'Employer', 'name_fa' => 'کارفرما'],
        ];
        foreach ($roles as $data) {
            Role::create($data);
        }
    }
}
