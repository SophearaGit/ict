<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'teacher',
                'khmer_name' => 'គ្រូបង្រៀន',
                'dob' => '1990-01-01',
                'email' => 'teacher@gmail.com',
                'phone' => '012000000',
                'gender' => 'male',
                'image' => 'no-img.jpg',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'approval_status' => 'approved',
                'document' => 'document.jpg',
                'status' => 'active',
                'location' => 'Phnom Penh',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'student',
                'khmer_name' => 'សិស្ស',
                'dob' => '2000-01-01',
                'email' => 'student@gmail.com',
                'phone' => '015000000',
                'gender' => 'female',
                'image' => 'no-img.jpg',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'student',
                'approval_status' => 'approved',
                'document' => '',
                'status' => 'active',
                'location' => 'Phnom Penh',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tha Channy',
                'khmer_name' => 'ថា ចន្នី',
                'dob' => '1995-07-21',
                'email' => 'ny@gmail.com',
                'phone' => '012000000',
                'gender' => 'female',
                'image' => '/default-images/teacher/Tha Channy.jpg',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'staff',
                'approval_status' => 'approved',
                'document' => 'document.jpg',
                'status' => 'active',
                'location' => 'Phnom Penh',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dalin',
                'khmer_name' => 'Dalin',
                'dob' => '1995-07-21',
                'email' => 'lin@gmail.com',
                'phone' => '012000000',
                'gender' => 'female',
                'image' => '',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'staff',
                'approval_status' => 'approved',
                'document' => 'document.jpg',
                'status' => 'active',
                'location' => 'Phnom Penh',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);
    }
}
