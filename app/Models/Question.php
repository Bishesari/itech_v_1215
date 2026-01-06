<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public const CLUSTERS = [
        'easy' => 'ساده',
        'medium' => 'متوسط',
        'hard' => 'سخت',
    ];
}
