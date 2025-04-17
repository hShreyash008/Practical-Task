<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), 
            'role' => 'admin',
            'email_verified_at' => now(), 
            'verification_code' => rand(100000, 999999), 
            'verification_code_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(), 
            'verification_code' => rand(100000, 999999),
            'verification_code_expires_at' => Carbon::now()->addMinutes(10),
        ]);
    }
}
