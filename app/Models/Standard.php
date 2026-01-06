<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Standard extends Model
{
    use HasJalaliDates;

    protected $fillable = ['field_id', 'code', 'name_fa', 'name_en', 'nazari_h', 'amali_h', 'karvarzi_h', 'project_h', 'required_h' ,'sum_h', 'is_active'];
    public function field():BelongsTo{
        return $this->belongsTo(Field::class);
    }
    public function chapters():HasMany{
        return $this->hasMany(Chapter::class)->orderBy('number');
    }

    public function questions():HasManyThrough
    {
        return $this->hasManyThrough(Question::class, Chapter::class);
    }
}
