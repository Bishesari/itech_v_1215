<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PageVisit extends Model
{
    protected $table = 'page_visits';

    protected $fillable = [
        'page_key',
        'fingerprint',
        'user_id',
        'ip',
        'user_agent',
        'is_bot',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * فقط بازدید کاربران لاگین شده
     */
    public function scopeAuthenticated(Builder $query): Builder
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * فقط بازدید مهمان‌ها
     */
    public function scopeGuest(Builder $query): Builder
    {
        return $query->whereNull('user_id');
    }

    /**
     * فقط بازدیدهای انسانی
     */
    public function scopeHuman(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }

    /**
     * فقط بازدیدهای بات
     */
    public function scopeBot(Builder $query): Builder
    {
        return $query->where('is_bot', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isGuest(): bool
    {
        return is_null($this->user_id);
    }

    public function isAuthenticated(): bool
    {
        return ! is_null($this->user_id);
    }
}
