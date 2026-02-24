<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Nhanh Nhim',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(), // optionally verify the email
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10), // optional
        ]);
    }
}
