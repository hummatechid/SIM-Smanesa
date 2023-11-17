<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::all()->each(function ($role) {
            $user = User::factory()->count(1)->create([
                'role_id' => $role->id,
                'email' => $role->name . '@gmail.com',
            ])->first();
            $user->assignRole($role->name);
        });
    }
}
