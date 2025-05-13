<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'  => 'MUHAMMAD UMER FAROOQ',
            'email' => 'umerf1417@g333mail.com',
            'password' => Hash::make('123456'), // Hashing the password
            'role'   => 'admin',
        ]);
    }
}
