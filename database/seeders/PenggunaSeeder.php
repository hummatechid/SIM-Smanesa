<?php

namespace Database\Seeders;

use App\Models\{User, Pengguna};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::all()->each(function ($role) {
            $user = User::where('email', $role->name.'@gmail.com')->first();

            $pengguna = Pengguna::create([
                'user_id' => $user->id,
                'nik' => time().time().time(),
                'full_name' => $role->name,
                'gender' => 'Laki-laki',
                'phone_number' => '',
                'address' => '',
                'religion' => ''
            ]);
        });
    }
}
