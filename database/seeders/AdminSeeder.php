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
        $admins = [
            [
                'name' => 'Nhanh Nhim',
                'email' => 'admin@gmail.com',
                'bio' => 'Founder & director of the ICT Center. Oversees curriculum, staff, and center operations.',
                'facebook' => 'https://facebook.com/nhanhnhim',
                'linkedin' => null,
                'twitter' => null,
                'instagram' => null,
                'youtube' => null,
                'website' => null,
            ],
            [
                'name' => 'Sok Dara',
                'email' => 'dara.admin@gmail.com',
                'bio' => 'Academic program coordinator. Handles course approvals, staff reports, and blog publishing.',
                'facebook' => null,
                'linkedin' => 'https://linkedin.com/in/sokdara',
                'twitter' => null,
                'instagram' => null,
                'youtube' => null,
                'website' => null,
            ],
        ];

        foreach ($admins as $admin) {
            // Admin::$fillable doesn't include 'email_verified_at' or
            // 'remember_token', so a plain updateOrCreate() would silently
            // drop both — forceFill() sets them anyway.
            $model = Admin::where('email', $admin['email'])->first() ?? new Admin();
            $model->forceFill([
                ...$admin,
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(10),
            ])->save();
        }
    }
}
