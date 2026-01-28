<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductAccess extends Pivot
{
    protected $table = 'product_access';

    protected $fillable = [
        'user_id',
        'product_id',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at'  => 'datetime', // یا immutable_datetime
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    /* ================= Relations ================= */

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ================= Scopes ================= */

    public function scopeValid($query)
    {
        return $query
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    /* ================= Domain Logic ================= */

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
