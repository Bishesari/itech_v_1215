<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name_en' => 'Newbie',         'name_fa' => 'تازه وارد',   'is_global'=> true,     'color'=>'teal'],
            ['name_en' => 'SuperAdmin',     'name_fa' => 'سوپر ادمین',  'is_global'=> true,     'color'=>'red'],
            ['name_en' => 'Founder',        'name_fa' => 'موسس',        'is_global'=> false,    'color'=>'rose'],
            ['name_en' => 'Manager',        'name_fa' => 'مدیر',        'is_global'=> false,    'color'=>'pink'],
            ['name_en' => 'Assistant',      'name_fa' => 'مسئول اداری', 'is_global'=> false,    'color'=>'fuchsia'],
            ['name_en' => 'Accountant',     'name_fa' => 'حسابدار',     'is_global'=> false,    'color'=>'orange'],
            ['name_en' => 'Teacher',        'name_fa' => 'مربی',        'is_global'=> false,    'color'=>'amber'],
            ['name_en' => 'Student',        'name_fa' => 'کارآموز',     'is_global'=> false,    'color'=>'lime'],
            ['name_en' => 'QuestionMaker',  'name_fa' => 'طراح سوال',   'is_global'=> false,    'color'=>'purple'],
            ['name_en' => 'QuestionAuditor','name_fa' => 'ممیز سوال',   'is_global'=> false,    'color'=>'violet'],
            ['name_en' => 'Examiner',       'name_fa' => 'آزمونگر',     'is_global'=> false,    'color'=>'yellow'],
            ['name_en' => 'Marketer',       'name_fa' => 'بازاریاب',    'is_global'=> true,     'color'=>'green'],
            ['name_en' => 'JobSeeker',      'name_fa' => 'کارجو',       'is_global'=> true,     'color'=>'emerald'],
            ['name_en' => 'Examinee',       'name_fa' => 'آزمون دهنده', 'is_global'=> true,     'color'=>'sky'],
            ['name_en' => 'Employer',       'name_fa' => 'کارفرما',     'is_global'=> true,     'color'=>'cyan'],
        ];
        foreach ($roles as $data) {
            Role::create($data);
        }
    }
}
