<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamAnswer extends Pivot
{
    public function examUser():BelongsTo
    {
        return $this->belongsTo(ExamUser::class);
    }

    public function question():BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function option():BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
