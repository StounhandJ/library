<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                "id" => 1,
                "name" => "user"
            ],
            [
                "id" => 2,
                "name" => "admin"
            ]
        ];
        foreach ($roles as $roleData) {
            $role = Role::query()->where("id", $roleData["id"])->firstOrNew();
            if (!$role->exists()) {
                Role::query()->make($roleData)->save();
            } else {
                $role->name = $roleData["name"];
                $role->save();
            }
        }
    }
}
