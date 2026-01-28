<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    use HasJalaliDates;
    protected $fillable = ['type', 'standard_id', 'title', 'que_qty', 'que_ids','start', 'duration', 'end', 'created_by'];

    public function questions():BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_question')->withTimestamps();
    }

    public function standard():BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }

    public function maker():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public const CLUSTERS = [
        'quiz' => 'کوئیز',
        'midterm' => 'میانترم',
        'final' => 'نهایی',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'exam_user')
            ->withPivot(['started_at', 'finished_at', 'score', 'question_order', 'created_at', 'updated_at'])
            ->withTimestamps();
    }


}
