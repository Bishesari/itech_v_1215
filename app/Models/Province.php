<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasJalaliDates;
    protected $fillable = ['name_fa', 'name_en'];
    public function cities():HasMany
    {
        return $this->hasMany(City::class);
    }
}
