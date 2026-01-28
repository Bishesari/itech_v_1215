<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamUser extends Pivot
{
    use HasJalaliDates;
    public function answers():HasMany
    {
        return $this->hasMany(ExamAnswer::class, 'exam_user_id');
    }
    public function exam():BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
