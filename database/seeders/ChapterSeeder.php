<?php

namespace Database\Seeders;

use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chapters = [
            ['standard_id' => '1', 'number' => '1', 'title' => 'نصب و راه اندازی Laravel'],
            ['standard_id' => '1', 'number' => '2', 'title' => 'ایجاد و ساخت مدل، نما، کنترلر و مسیريابی'],
            ['standard_id' => '1', 'number' => '3', 'title' => 'ایجاد و ساخت Migration'],
            ['standard_id' => '1', 'number' => '4', 'title' => 'قالب سازی با Blade در فریمورک لاراول'],
            ['standard_id' => '1', 'number' => '5', 'title' => 'ایجاد کردن نگاشت رابطه به شی'],
            ['standard_id' => '1', 'number' => '6', 'title' => 'ایجاد و ساخت کوئری با Query Builder'],
            ['standard_id' => '1', 'number' => '7', 'title' => 'اعتبارسنجی فرم ها در لاراول'],
            ['standard_id' => '2', 'number' => '1', 'title' => 'نصب برنامه پایتون و روشهای اجرای کد در آن'],
            ['standard_id' => '2', 'number' => '2', 'title' => 'کار با متغییرها، عبارات و دستورات'],
            ['standard_id' => '2', 'number' => '3', 'title' => 'کار با ساختمان داده ها در پایتون'],
            ['standard_id' => '2', 'number' => '4', 'title' => 'کار با شرط ها و حلقه های تکرار'],
            ['standard_id' => '2', 'number' => '5', 'title' => 'بررسی Function ها در زبان پایتون'],
            ['standard_id' => '2', 'number' => '6', 'title' => 'کار با فایل ها'],
            ['standard_id' => '2', 'number' => '7', 'title' => 'بررسی Map و استفاده از Lambda'],
            ['standard_id' => '2', 'number' => '8', 'title' => 'کار با انواع فرمت ها Csv و JSON و XML'],
            ['standard_id' => '2', 'number' => '9', 'title' => 'شی گرایی در پايتون'],
            ['standard_id' => '2', 'number' => '10', 'title' => 'ساخت بانک اطلاعاتی و دستورات MySQL'],
            ['standard_id' => '2', 'number' => '11', 'title' => 'واسط گرافیکی در زبان پایتون'],
        ];
        foreach ($chapters as $data) {
            Chapter::create($data);
        }
    }
}
