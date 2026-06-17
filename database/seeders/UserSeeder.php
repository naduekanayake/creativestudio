<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@creativestudio.lk'],
            [
                'name'     => 'John Perera',
                'email'    => 'admin@creativestudio.lk',
                'password' => Hash::make('password123'),
                'role'     => 'super_admin',
                'position' => 'Studio Owner',
                'is_active'=> true,
            ]
        );
    }
}