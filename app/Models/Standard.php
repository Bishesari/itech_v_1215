<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Standard extends Model
{
    use HasJalaliDates;

    public function field():BelongsTo{
        return $this->belongsTo(Field::class);
    }
}
