<?php

namespace App\Models\Products;

use App\Models\Order;
use App\Models\ProductAccess;
use App\Models\Standard;
use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasJalaliDates;
    protected $fillable = ['category_id', 'parent_id', 'title', 'slug', 'price', 'duration_days', 'is_active', 'description'];



    /* ================= Relations ================= */

    public function accesses(): HasMany
    {
        return $this->hasMany(ProductAccess::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function standard(): BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function writtenQuestion():HasOne
    {
        return $this->hasOne(WrittenQuestionProduct::class, 'product_id');
    }

    /* ================= Business Logic ================= */

    public function hasAccess(?int $userId = null): bool
    {
        $userId ??= auth()->id();

        if (! $userId) {
            return false;
        }

        return $this->accesses()
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn (Builder $q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->exists();
    }
}

