<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasJalaliDates;
    protected $fillable = ['province_id', 'name_fa', 'name_en'];
}
