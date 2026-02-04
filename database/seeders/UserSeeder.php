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
        $user = [
            [
                'name' => 'Chantheoun Sokvibol',
                'email' => 'chantheoun.sokvibol@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Muth Manou',
                'email' => 'muth.manou@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sin Many',
                'email' => 'sin.many@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Khorn sopheap',
                'email' => 'khorn.sopheap@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Srun Borath',
                'email' => 'srun.borath@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hong Kimleng',
                'email' => 'hong.kimleng@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phally Makara',
                'email' => 'phally.makara@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Yeab Chanya',
                'email' => 'yeab.chanya@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lay Longhav',
                'email' => 'lay.longhav@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ang Kimsor',
                'email' => 'ang.kimsor@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ngan Channa',
                'email' => 'ngan.channa@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chea Dane',
                'email' => 'chea.dane@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tiv Sovithyea',
                'email' => 'tiv.sovithyea@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'student',
                'email' => 'student@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'student',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'teacher',
                'email' => 'teacher@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        User::insert($user);
    }
}
