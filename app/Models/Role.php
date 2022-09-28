<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ["id", "name"];

    public $timestamps = false;

    public $incrementing = false;

    public static function getAllRoles(): array
    {
        $roles = [];
        foreach (Role::all("name") as $role) {
            $roles[] = $role["name"];
        }

        return $roles;
    }
}
