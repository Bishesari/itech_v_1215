<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name_en' => 'Newbie', 'name_fa' => 'تازه وارد', 'color'=>'teal'],
            ['name_en' => 'SuperAdmin', 'name_fa' => 'سوپر ادمین', 'color'=>'red'],
            ['name_en' => 'Founder', 'name_fa' => 'موسس', 'color'=>'rose'],
            ['name_en' => 'Manager', 'name_fa' => 'مدیر', 'color'=>'pink'],
            ['name_en' => 'Assistant', 'name_fa' => 'مسئول اداری', 'color'=>'fuchsia'],
            ['name_en' => 'Accountant', 'name_fa' => 'حسابدار', 'color'=>'orange'],
            ['name_en' => 'Teacher', 'name_fa' => 'مربی', 'color'=>'amber'],
            ['name_en' => 'Student', 'name_fa' => 'کارآموز', 'color'=>'lime'],
            ['name_en' => 'QuestionMaker', 'name_fa' => 'طراح سوال', 'color'=>'purple'],
            ['name_en' => 'QuestionAuditor', 'name_fa' => 'ممیز سوال', 'color'=>'violet'],
            ['name_en' => 'Examiner', 'name_fa' => 'آزمونگر', 'color'=>'yellow'],
            ['name_en' => 'Marketer', 'name_fa' => 'بازاریاب', 'color'=>'green'],
            ['name_en' => 'JobSeeker', 'name_fa' => 'کارجو', 'color'=>'emerald'],
            ['name_en' => 'Examinee', 'name_fa' => 'آزمون دهنده', 'color'=>'sky'],
            ['name_en' => 'Employer', 'name_fa' => 'کارفرما', 'color'=>'cyan'],
        ];
        foreach ($roles as $data) {
            Role::create($data);
        }
    }
}
