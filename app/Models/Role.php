<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasJalaliDates;
    protected $fillable = ['name_fa', 'name_en'];
}
