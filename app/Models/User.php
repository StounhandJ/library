<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'login',
        'password',
        'birthday',
        'role_id',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role_id',
    ];

    protected $appends = ["favorites_books", "role_name"];

    public function getFavoritesBooksAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->favorites_books()->get();
    }

    public function getRoleNameAttribute(): string
    {
        return Role::query()->find($this->role_id)->name;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public static function getByLogin($login): User|\Illuminate\Database\Eloquent\Builder
    {
        return User::query()
            ->where("login", $login)
            ->firstOrNew();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [

        ];
    }

    public function favorites_books(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'favorites_books');
    }

    public function checkRole(array|string $roles): bool
    {
        $role_name = Role::query()->where("id", $this->role_id)->firstOrNew()->name;
        if (is_array($roles)) {
            return in_array($role_name, $roles);
        } else {
            return $role_name == $roles;
        }
    }
}
