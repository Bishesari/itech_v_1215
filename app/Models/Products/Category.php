<?php

namespace App\Models\Products;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasJalaliDates;
    protected $fillable = ['title', 'slug', 'is_subscription', 'is_repeatable', 'has_duration'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
