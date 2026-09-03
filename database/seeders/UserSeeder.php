<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Builds a roster big enough to exercise every dashboard: several
     * instructors (so courses aren't all taught by the same person),
     * a handful of staff (some with report-review rights), several
     * interns, and enough students to make pagination, attendance,
     * and reports mean something.
     */
    public function run(): void
    {
        $now = now();

        // ---------------------------------------------------------------
        // Fixed, "flagship" accounts — kept from the original seeder so
        // existing bookmarks / login credentials people already use
        // (teacher@gmail.com, student@gmail.com, etc.) keep working.
        // ---------------------------------------------------------------
        $fixed = [
            [
                'name' => 'teacher',
                'khmer_name' => 'គ្រូបង្រៀន',
                'dob' => '1990-01-01',
                'email' => 'teacher@gmail.com',
                'phone' => '012000000',
                'gender' => 'male',
                'role' => 'instructor',
                'designation' => 'Full-Stack Web Development Instructor',
                'expertise' => ['Laravel', 'PHP', 'MySQL', 'REST APIs'],
                'location' => 'Phnom Penh',
            ],
            [
                'name' => 'student',
                'khmer_name' => 'សិស្ស',
                'dob' => '2000-01-01',
                'email' => 'student@gmail.com',
                'phone' => '015000000',
                'gender' => 'female',
                'role' => 'student',
                'location' => 'Phnom Penh',
            ],
            [
                'name' => 'staff',
                'khmer_name' => 'បុគ្គលិក',
                'dob' => '1995-07-21',
                'email' => 'staff@gmail.com',
                'phone' => '089988876',
                'gender' => 'female',
                'role' => 'staff',
                'admin_approval_edit_staff' => true,
                'location' => 'Phnom Penh',
            ],
            [
                'name' => 'Tha Channy',
                'khmer_name' => 'ថា ចន្នី',
                'dob' => '1995-07-21',
                'email' => 'ny@gmail.com',
                'phone' => '012000000',
                'gender' => 'female',
                'role' => 'staff',
                'admin_approval_edit_staff' => true,
                'image' => '/default-images/teacher/Tha Channy.jpg',
                'location' => 'Phnom Penh',
            ],
            [
                'name' => 'Dalin',
                'khmer_name' => 'Dalin',
                'dob' => '1995-07-21',
                'email' => 'lin@gmail.com',
                'phone' => '012000000',
                'gender' => 'female',
                'role' => 'staff',
                'location' => 'Phnom Penh',
            ],
            [
                'name' => 'intern',
                'khmer_name' => 'សុខា',
                'dob' => '1998-03-15',
                'email' => 'intern@gmail.com',
                'phone' => '011111111',
                'gender' => 'male',
                'role' => 'intern',
                'location' => 'Phnom Penh',
            ],
        ];

        // ---------------------------------------------------------------
        // More instructors — courses shouldn't all belong to one teacher.
        // ---------------------------------------------------------------
        $instructors = [
            ['name' => 'Ratanak Sok', 'gender' => 'male', 'designation' => 'Backend & Database Instructor', 'expertise' => ['PHP', 'Laravel', 'MySQL', 'API Design']],
            ['name' => 'Bopha Chan', 'gender' => 'female', 'designation' => 'UI/UX & Frontend Instructor', 'expertise' => ['Figma', 'HTML5', 'CSS3', 'JavaScript', 'React']],
            ['name' => 'Vibol Heng', 'gender' => 'male', 'designation' => 'Networking & Systems Instructor', 'expertise' => ['Networking', 'Linux', 'Cloud Computing', 'DevOps']],
        ];

        // ---------------------------------------------------------------
        // More staff — a couple with report-review rights so the
        // "report.grant" middleware actually has more than one admin
        // to test against.
        // ---------------------------------------------------------------
        $staff = [
            ['name' => 'Sokha Meas', 'gender' => 'female', 'grant' => true],
            ['name' => 'Pisey Kim', 'gender' => 'male', 'grant' => false],
        ];

        // ---------------------------------------------------------------
        // More interns.
        // ---------------------------------------------------------------
        $interns = [
            ['name' => 'Ratha Suon', 'gender' => 'male'],
            ['name' => 'Sreymom Ros', 'gender' => 'female'],
            ['name' => 'Chenda Va', 'gender' => 'female'],
        ];

        // ---------------------------------------------------------------
        // A pool of romanized Khmer-style names for bulk student data.
        // (khmer_name is left blank for generated students rather than
        // risk incorrect Khmer script for names nobody actually gave us.)
        // ---------------------------------------------------------------
        $firstNames = ['Sokha', 'Chan', 'Dara', 'Sopheak', 'Bopha', 'Chenda', 'Rithy', 'Vanna', 'Kunthea', 'Pisey', 'Rachana', 'Sophal', 'Mengly', 'Sreymom', 'Vibol', 'Chanthou', 'Ratanak', 'Leakhena', 'Phirom', 'Sreynich', 'Vuthy', 'Malis', 'Rotha', 'Kanha'];
        $lastNames = ['Chea', 'Chan', 'Heng', 'Keo', 'Kim', 'Lim', 'Meas', 'Nhem', 'Ou', 'Pich', 'Ros', 'Sar', 'Sok', 'Suon', 'Tep', 'Va', 'Yin'];
        $cities = ['Phnom Penh', 'Siem Reap', 'Battambang', 'Kampong Cham', 'Kampong Speu', 'Preah Sihanouk', 'Takeo'];

        $students = [];
        $studentCount = 24;
        for ($i = 1; $i <= $studentCount; $i++) {
            $first = $firstNames[($i - 1) % count($firstNames)];
            $last = $lastNames[($i * 3 - 1) % count($lastNames)];
            $gender = $i % 2 === 0 ? 'male' : 'female';

            // Mostly approved & active — a few pending/rejected/on-leave so
            // the admin's student-approval screen isn't a wall of green.
            $approvalStatus = match (true) {
                $i % 11 === 0 => 'rejected',
                $i % 7 === 0 => 'pending',
                default => 'approved',
            };
            $status = $i % 9 === 0 ? 'on_leave' : 'active';

            $students[] = [
                'name' => "{$first} {$last}",
                'gender' => $gender,
                'email' => 'student' . ($i + 1) . '@gmail.com',
                'phone' => '01' . str_pad((string) (7000000 + $i), 7, '0', STR_PAD_LEFT),
                'dob' => sprintf('%d-%02d-%02d', rand(1998, 2007), rand(1, 12), rand(1, 28)),
                'location' => $cities[$i % count($cities)],
                'role' => 'student',
                'approval_status' => $approvalStatus,
                'status' => $status,
            ];
        }

        $rows = [];

        foreach ($fixed as $user) {
            $rows[] = $this->buildRow($user, $now);
        }

        foreach ($instructors as $user) {
            $rows[] = $this->buildRow([
                ...$user,
                'role' => 'instructor',
                'location' => 'Phnom Penh',
            ], $now);
        }

        foreach ($staff as $user) {
            $rows[] = $this->buildRow([
                'name' => $user['name'],
                'gender' => $user['gender'],
                'role' => 'staff',
                'admin_approval_edit_staff' => $user['grant'],
                'location' => 'Phnom Penh',
            ], $now);
        }

        foreach ($interns as $user) {
            $rows[] = $this->buildRow([
                'name' => $user['name'],
                'gender' => $user['gender'],
                'role' => 'intern',
                'location' => 'Phnom Penh',
            ], $now);
        }

        foreach ($students as $user) {
            $rows[] = $this->buildRow($user, $now);
        }

        foreach ($rows as $row) {
            User::updateOrCreate(['email' => $row['email']], $row);
        }
    }

    /**
     * Fill in sensible defaults for anything the caller didn't specify.
     *
     * @param array<string, mixed> $user
     * @return array<string, mixed>
     */
    private function buildRow(array $user, $now): array
    {
        $name = $user['name'];

        return [
            'name' => $name,
            'khmer_name' => $user['khmer_name'] ?? null,
            'dob' => $user['dob'] ?? '2000-01-01',
            'email' => $user['email'] ?? Str::slug($name, '.') . '@gmail.com',
            'phone' => $user['phone'] ?? '01' . rand(1000000, 9999999),
            'gender' => $user['gender'] ?? 'male',
            'image' => $user['image'] ?? 'no-img.jpg',
            'email_verified_at' => $now,
            'password' => bcrypt('12345678'),
            'role' => $user['role'],
            'designation' => $user['designation'] ?? null,
            'expertise' => $user['expertise'] ?? null,
            'approval_status' => $user['approval_status'] ?? 'approved',
            'admin_approval_edit_staff' => $user['admin_approval_edit_staff'] ?? false,
            'document' => $user['document'] ?? 'document.jpg',
            'status' => $user['status'] ?? 'active',
            'location' => $user['location'] ?? 'Phnom Penh',
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
