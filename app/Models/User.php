<?php

namespace App\Models;

use App\Traits\HasJalaliDates;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use Notifiable, TwoFactorAuthenticatable, HasJalaliDates;

    protected $fillable = ['user_name', 'password'];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ================= Accessors ================= */

    public function initials(): string
    {
        if (! $this->profile?->f_name_fa) {
            return '';
        }

        return Str::of($this->profile->f_name_fa)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function full_fa_name(): string
    {
        return $this->profile?->f_name_fa
            ? trim($this->profile->f_name_fa . ' ' . $this->profile->l_name_fa)
            : $this->user_name;
    }

    /* ================= Relations ================= */

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class)
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_user')
            ->withPivot(['id', 'started_at', 'finished_at', 'score', 'question_order'])
            ->withTimestamps();
    }

    public function productAccesses(): HasMany
    {
        return $this->hasMany(ProductAccess::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /* ================= Business ================= */

    public function getAllRolesWithBranches()
    {
        return DB::table('branch_role_user')
            ->join('roles', 'branch_role_user.role_id', '=', 'roles.id')
            ->leftJoin('branches', 'branch_role_user.branch_id', '=', 'branches.id')
            ->where('branch_role_user.user_id', $this->id)
            ->select(
                'branch_role_user.id',
                'roles.id as role_id',
                'roles.name_fa as role_name',
                'branches.id as branch_id',
                'branches.short_name as branch_name',
                'roles.color as role_color'
            )
            ->get();
    }
}

