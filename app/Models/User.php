<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use Notifiable, TwoFactorAuthenticatable;
    protected $fillable = ['user_name', 'password'];
    protected $hidden = ['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function profile():HasOne
    {
        return $this->hasOne(Profile::class);
    }
    public function contacts():BelongsToMany
    {
        return $this->belongsToMany(Contact::class)
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function getAllRolesWithBranches()
    {
        return DB::table('branch_role_user')
            ->join('roles', 'branch_role_user.role_id', '=', 'roles.id')
            ->leftJoin('branches', 'branch_role_user.branch_id', '=', 'branches.id')
            ->where('branch_role_user.user_id', $this->id)
            ->select(
                'roles.id as role_id', 'roles.name_fa as role_name', 'branches.id as branch_id', 'branches.name as branch_name'
            )
            ->get();
    }

    public function roles():BelongsToMany
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps();
    }
}
