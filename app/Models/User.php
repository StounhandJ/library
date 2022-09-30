<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, Searchable;

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
        'avatar_path',
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
        "created_at",
        "deleted_at",
        "updated_at",
        "avatar_path",
    ];

    protected $appends = ["favorites_books", "role_name", "avatar_url"];

    public function toSearchableArray(): array
    {
        return [
            "login" => $this->login,
            "surname" => $this->surname,
            "name" => $this->name,
            "patronymic" => $this->patronymic,
        ];
    }

    public function getBirthdayAttribute(): string|null
    {
        if (is_null($this->attributes["birthday"]))
            return null;
        return Carbon::make($this->attributes["birthday"])->dateName();
    }

    public function getAvatarUrlAttribute(): string|null
    {
        if (is_null($this->avatar_path))
            return null;
        return Request::root() . $this->getAvatarSrc();
    }

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

    public function getAvatarSrc(): string
    {
        return Storage::disk("avatar")->url($this->avatar_path);
    }

    /**
     * Sets the new image src.
     *
     * @param UploadedFile|string|null $img
     */
    public function setImgSrcIfNotEmpty(UploadedFile|string|null $img)
    {
        if (!is_null($img) and ((is_string($img) and $img != "") or !is_string($img))) {
            $this->avatar_path = User::saveImg($img);
        }
    }

    /**
     * The photo is saved on the disk. Return src.
     *
     * @param UploadedFile|string $img
     * @return string
     */
    public static function saveImg(UploadedFile|string $img): string
    {
        return Storage::disk("avatar")->put("/", $img, "public");
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
