<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use HasJalaliDates;
    protected $fillable = ['code', 'abbr', 'short_name', 'full_name', 'province_id', 'city_id', 'address', 'postal_code', 'phone', 'mobile', 'credit_balance', 'is_active'];

    public function province():BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
    public function city():BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
