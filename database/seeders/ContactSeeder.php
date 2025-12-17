<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $mobiles = [
            ['value'=>'09177755924', 'is_verified'=>true],
            ['value'=>'09034336111']
        ];
        foreach ($mobiles as $data) {
            Contact::create($data);
        }
    }
}
