<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ICTCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i_c_t_courses = [
            [
                'id' => 1,
                'instructor_id' => 13,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'Web Development ( Frontend )',
                'slug' => 'web-development-frontend',
                'description' => null,
                'start_date' => '',
                'end_date' => '',
                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 03:30:18',
                'updated_at' => '2026-03-04 03:30:18',
            ],
            [
                'id' => 2,
                'instructor_id' => 13,
                'schedule_id' => 10,
                'thumbnail' => '',
                'title' => 'Web Development ( Frontend )',
                'slug' => 'web-development-frontend',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 03:31:39',
                'updated_at' => '2026-03-04 03:31:39',
            ],
            [
                'id' => 3,
                'instructor_id' => 13,
                'schedule_id' => 13,
                'thumbnail' => '',
                'title' => 'Web Development ( Frontend )',
                'slug' => 'web-development-frontend',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 03:32:42',
                'updated_at' => '2026-03-04 03:32:42',
            ],
            [
                'id' => 4,
                'instructor_id' => 5,
                'schedule_id' => 10,
                'thumbnail' => '',
                'title' => 'Web Development ( Backend )',
                'slug' => 'web-development-backend',
                'description' => null,


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 03:41:17',
                'updated_at' => '2026-03-04 03:41:17',
            ],
            [
                'id' => 5,
                'instructor_id' => 5,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'Web Development ( Backend )',
                'slug' => 'web-development-backend',
                'description' => '',


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 03:44:05',
                'updated_at' => '2026-03-04 03:44:58',
            ],
            [
                'id' => 6,
                'instructor_id' => 12,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'C++ Programming',
                'slug' => 'c-programming',
                'description' => '',


                'price' => 89,
                'status' => 'active',
                'created_at' => '2026-03-04 03:47:16',
                'updated_at' => '2026-03-04 03:47:16',
            ],
            [
                'id' => 7,
                'instructor_id' => 12,
                'schedule_id' => 13,
                'thumbnail' => '',
                'title' => 'C++ Programming',
                'slug' => 'c-programming',
                'description' => '',


                'price' => 89,
                'status' => 'active',
                'created_at' => '2026-03-04 03:48:12',
                'updated_at' => '2026-03-04 03:48:12',
            ],
            [
                'id' => 8,
                'instructor_id' => 12,
                'schedule_id' => 10,
                'thumbnail' => '',
                'title' => 'C++ Programming',
                'slug' => 'c-programming',
                'description' => null,


                'price' => 89,
                'status' => 'active',
                'created_at' => '2026-03-04 03:50:15',
                'updated_at' => '2026-03-04 03:50:15',
            ],
            [
                'id' => 9,
                'instructor_id' => 4,
                'schedule_id' => 10,
                'thumbnail' => '',
                'title' => 'C# Programming',
                'slug' => 'c-programming',
                'description' => '',


                'price' => 159,
                'status' => 'active',
                'created_at' => '2026-03-04 03:54:20',
                'updated_at' => '2026-03-04 03:54:20',
            ],
            [
                'id' => 10,
                'instructor_id' => 4,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'C# Programming',
                'slug' => 'c-programming',
                'description' => null,


                'price' => 159,
                'status' => 'active',
                'created_at' => '2026-03-04 04:02:27',
                'updated_at' => '2026-03-04 04:02:27',
            ],
            [
                'id' => 11,
                'instructor_id' => 7,
                'schedule_id' => 7,
                'thumbnail' => '',
                'title' => 'UX/UI Design',
                'slug' => 'uxui-design',
                'description' => null,


                'price' => 119,
                'status' => 'active',
                'created_at' => '2026-03-04 04:06:58',
                'updated_at' => '2026-03-04 04:06:58',
            ],
            [
                'id' => 12,
                'instructor_id' => 11,
                'schedule_id' => 8,
                'thumbnail' => '',
                'title' => 'UX/UI Design',
                'slug' => 'uxui-design',
                'description' => null,


                'price' => 119,
                'status' => 'active',
                'created_at' => '2026-03-04 04:15:04',
                'updated_at' => '2026-03-04 04:18:39',
            ],
            [
                'id' => 13,
                'instructor_id' => 11,
                'schedule_id' => 12,
                'thumbnail' => '',
                'title' => 'UX/UI Design',
                'slug' => 'uxui-design',
                'description' => null,


                'price' => 119,
                'status' => 'active',
                'created_at' => '2026-03-04 04:16:14',
                'updated_at' => '2026-03-04 04:16:14',
            ],
            [
                'id' => 14,
                'instructor_id' => 8,
                'schedule_id' => 3,
                'thumbnail' => '',
                'title' => 'UX/UI Design',
                'slug' => 'uxui-design',
                'description' => null,


                'price' => 119,
                'status' => 'active',
                'created_at' => '2026-03-04 04:17:05',
                'updated_at' => '2026-03-04 04:17:05',
            ],
            [
                'id' => 15,
                'instructor_id' => 14,
                'schedule_id' => 7,
                'thumbnail' => '',
                'title' => 'Mobile App',
                'slug' => 'mobile-app',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:24:28',
                'updated_at' => '2026-03-04 04:30:52',
            ],
            [
                'id' => 16,
                'instructor_id' => 14,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'Mobile App',
                'slug' => 'mobile-app',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:33:04',
                'updated_at' => '2026-03-04 04:33:04',
            ],
            [
                'id' => 17,
                'instructor_id' => 14,
                'schedule_id' => 13,
                'thumbnail' => '',
                'title' => 'Mobile App',
                'slug' => 'mobile-app',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:33:49',
                'updated_at' => '2026-03-04 04:33:49',
            ],
            [
                'id' => 18,
                'instructor_id' => 14,
                'schedule_id' => 13,
                'thumbnail' => '',
                'title' => 'Graphic Design',
                'slug' => 'graphic-design',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:44:00',
                'updated_at' => '2026-03-04 04:44:00',
            ],
            [
                'id' => 19,
                'instructor_id' => 14,
                'schedule_id' => 7,
                'thumbnail' => '',
                'title' => 'Graphic Design',
                'slug' => 'graphic-design',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:44:35',
                'updated_at' => '2026-03-04 04:44:35',
            ],
            [
                'id' => 20,
                'instructor_id' => 14,
                'schedule_id' => 12,
                'thumbnail' => '',
                'title' => 'Python',
                'slug' => 'python',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:46:25',
                'updated_at' => '2026-03-04 04:46:25',
            ],
            [
                'id' => 21,
                'instructor_id' => 14,
                'schedule_id' => 10,
                'thumbnail' => '',
                'title' => 'Python',
                'slug' => 'python',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:48:24',
                'updated_at' => '2026-03-04 04:48:24',
            ],
            [
                'id' => 22,
                'instructor_id' => 14,
                'schedule_id' => 7,
                'thumbnail' => '',
                'title' => 'Python',
                'slug' => 'python',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:48:52',
                'updated_at' => '2026-03-04 04:48:52',
            ],
            [
                'id' => 23,
                'instructor_id' => 14,
                'schedule_id' => 12,
                'thumbnail' => '',
                'title' => 'Data Analysist',
                'slug' => 'data-analysist',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 04:53:47',
                'updated_at' => '2026-03-04 04:53:47',
            ],
            [
                'id' => 24,
                'instructor_id' => 14,
                'schedule_id' => 7,
                'thumbnail' => '',
                'title' => 'Data Analysist',
                'slug' => 'data-analysist',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 04:54:22',
                'updated_at' => '2026-03-04 04:54:22',
            ],
            [
                'id' => 25,
                'instructor_id' => 14,
                'schedule_id' => 12,
                'thumbnail' => '',
                'title' => 'Java I',
                'slug' => 'java-i',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:55:47',
                'updated_at' => '2026-03-04 04:55:47',
            ],
            [
                'id' => 26,
                'instructor_id' => 14,
                'schedule_id' => 10,
                'thumbnail' => '',
                'title' => 'Java I',
                'slug' => 'java-i',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 04:56:35',
                'updated_at' => '2026-03-04 04:56:35',
            ],
            [
                'id' => 27,
                'instructor_id' => 14,
                'schedule_id' => 8,
                'thumbnail' => '',
                'title' => 'Web Development-Backend (  NodeJs )',
                'slug' => 'web-development-backend-nodejs',
                'description' => null,


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 06:41:28',
                'updated_at' => '2026-03-04 06:41:28',
            ],
            [
                'id' => 28,
                'instructor_id' => 14,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'Full Stack Web Development',
                'slug' => 'full-stack-web-development',
                'description' => null,


                'price' => 159,
                'status' => 'active',
                'created_at' => '2026-03-04 06:42:48',
                'updated_at' => '2026-03-04 06:42:48',
            ],
            [
                'id' => 29,
                'instructor_id' => 14,
                'schedule_id' => 1,
                'thumbnail' => '',
                'title' => 'Full Stack Web Development',
                'slug' => 'full-stack-web-development',
                'description' => null,


                'price' => 159,
                'status' => 'active',
                'created_at' => '2026-03-04 06:43:45',
                'updated_at' => '2026-03-04 06:43:45',
            ],
            [
                'id' => 30,
                'instructor_id' => 14,
                'schedule_id' => 13,
                'thumbnail' => '',
                'title' => 'Full Stack Web Development',
                'slug' => 'full-stack-web-development',
                'description' => null,


                'price' => 159,
                'status' => 'active',
                'created_at' => '2026-03-04 06:44:40',
                'updated_at' => '2026-03-04 06:44:40',
            ],
            [
                'id' => 31,
                'instructor_id' => 14,
                'schedule_id' => 1,
                'thumbnail' => '',
                'title' => 'Advance of Excel',
                'slug' => 'advance-of-excel',
                'description' => null,


                'price' => 99,
                'status' => 'active',
                'created_at' => '2026-03-04 06:45:29',
                'updated_at' => '2026-03-04 06:45:29',
            ],
            [
                'id' => 32,
                'instructor_id' => 14,
                'schedule_id' => 1,
                'thumbnail' => '',
                'title' => 'Database Management System',
                'slug' => 'database-management-system',
                'description' => null,


                'price' => 159,
                'status' => 'active',
                'created_at' => '2026-03-04 06:46:18',
                'updated_at' => '2026-03-04 06:46:18',
            ],
            [
                'id' => 33,
                'instructor_id' => 14,
                'schedule_id' => 11,
                'thumbnail' => '',
                'title' => 'Networking',
                'slug' => 'networking',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 06:47:19',
                'updated_at' => '2026-03-04 06:47:19',
            ],
            [
                'id' => 34,
                'instructor_id' => 14,
                'schedule_id' => 14,
                'thumbnail' => '',
                'title' => 'Networking',
                'slug' => 'networking',
                'description' => null,


                'price' => 129,
                'status' => 'active',
                'created_at' => '2026-03-04 07:00:06',
                'updated_at' => '2026-03-04 07:00:06',
            ],
            [
                'id' => 35,
                'instructor_id' => 14,
                'schedule_id' => 15,
                'thumbnail' => '',
                'title' => 'Microsoft Office',
                'slug' => 'microsoft-office',
                'description' => null,


                'price' => 79,
                'status' => 'active',
                'created_at' => '2026-03-04 07:03:45',
                'updated_at' => '2026-03-04 07:03:45',
            ],
            [
                'id' => 36,
                'instructor_id' => 14,
                'schedule_id' => 9,
                'thumbnail' => '',
                'title' => 'Excel VBA',
                'slug' => 'excel-vba',
                'description' => null,


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 07:04:38',
                'updated_at' => '2026-03-04 07:04:38',
            ],
            [
                'id' => 37,
                'instructor_id' => 14,
                'schedule_id' => 1,
                'thumbnail' => '',
                'title' => 'Excel VBA',
                'slug' => 'excel-vba',
                'description' => null,


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 07:05:20',
                'updated_at' => '2026-03-04 07:05:20',
            ],
            [
                'id' => 38,
                'instructor_id' => 14,
                'schedule_id' => 2,
                'thumbnail' => '',
                'title' => 'Java Backend ( Spring Boot )',
                'slug' => 'java-backend-spring-boot',
                'description' => null,


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 07:23:55',
                'updated_at' => '2026-03-04 07:23:55',
            ],
            [
                'id' => 39,
                'instructor_id' => 14,
                'schedule_id' => 12,
                'thumbnail' => '',
                'title' => 'Java Backend ( Spring Boot )',
                'slug' => 'java-backend-spring-boot',
                'description' => null,


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 07:24:52',
                'updated_at' => '2026-03-04 07:24:52',
            ],
            [
                'id' => 40,
                'instructor_id' => 14,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'Cloud Computing',
                'slug' => 'cloud-computing',
                'description' => null,


                'price' => 159,
                'status' => 'active',
                'created_at' => '2026-03-04 07:25:44',
                'updated_at' => '2026-03-04 07:25:44',
            ],
            [
                'id' => 41,
                'instructor_id' => 14,
                'schedule_id' => 6,
                'thumbnail' => '',
                'title' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'description' => null,


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 07:27:09',
                'updated_at' => '2026-03-04 07:27:09',
            ],
            [
                'id' => 42,
                'instructor_id' => 14,
                'schedule_id' => 12,
                'thumbnail' => '',
                'title' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'description' => null,


                'price' => 139,
                'status' => 'active',
                'created_at' => '2026-03-04 07:27:56',
                'updated_at' => '2026-03-04 07:27:56',
            ],
            [
                'id' => 43,
                'instructor_id' => 14,
                'schedule_id' => 16,
                'thumbnail' => '',
                'title' => 'Deep Learning',
                'slug' => 'deep-learning',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:34:27',
                'updated_at' => '2026-03-04 07:34:27',
            ],
            [
                'id' => 44,
                'instructor_id' => 14,
                'schedule_id' => 8,
                'thumbnail' => '',
                'title' => 'Deep Learning',
                'slug' => 'deep-learning',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:34:57',
                'updated_at' => '2026-03-04 07:34:57',
            ],
            [
                'id' => 45,
                'instructor_id' => 14,
                'schedule_id' => 6,
                'thumbnail' => '',
                'title' => 'IT Project Management',
                'slug' => 'it-project-management',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:36:09',
                'updated_at' => '2026-03-04 07:36:09',
            ],
            [
                'id' => 46,
                'instructor_id' => 14,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'IT Project Management',
                'slug' => 'it-project-management',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:36:46',
                'updated_at' => '2026-03-04 07:36:46',
            ],
            [
                'id' => 47,
                'instructor_id' => 14,
                'schedule_id' => 10,
                'thumbnail' => '',
                'title' => 'Video Editing',
                'slug' => 'video-editing',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:37:56',
                'updated_at' => '2026-03-04 07:37:56',
            ],
            [
                'id' => 48,
                'instructor_id' => 14,
                'schedule_id' => 5,
                'thumbnail' => '',
                'title' => 'Video Editiing',
                'slug' => 'video-editiing',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:38:27',
                'updated_at' => '2026-03-04 07:38:27',
            ],
            [
                'id' => 49,
                'instructor_id' => 14,
                'schedule_id' => 8,
                'thumbnail' => '',
                'title' => 'C Programming',
                'slug' => 'c-programming',
                'description' => null,


                'price' => 119,
                'status' => 'active',
                'created_at' => '2026-03-04 07:39:23',
                'updated_at' => '2026-03-04 07:39:23',
            ],
            [
                'id' => 50,
                'instructor_id' => 14,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'DevOps',
                'slug' => 'devops',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:40:02',
                'updated_at' => '2026-03-04 07:40:02',
            ],
            [
                'id' => 51,
                'instructor_id' => 14,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'Microsoft Power BI',
                'slug' => 'microsoft-power-bi',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:41:09',
                'updated_at' => '2026-03-04 07:41:09',
            ],
            [
                'id' => 52,
                'instructor_id' => 14,
                'schedule_id' => 10,
                'thumbnail' => '',
                'title' => 'Microsoft Power BI',
                'slug' => 'microsoft-power-bi',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:42:20',
                'updated_at' => '2026-03-04 07:42:20',
            ],
            [
                'id' => 53,
                'instructor_id' => 14,
                'schedule_id' => 4,
                'thumbnail' => '',
                'title' => 'AI & Machine Learning',
                'slug' => 'ai-machine-learning',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:43:35',
                'updated_at' => '2026-03-04 07:43:35',
            ],
            [
                'id' => 54,
                'instructor_id' => 14,
                'schedule_id' => 8,
                'thumbnail' => '',
                'title' => 'AI & Machine Learning',
                'slug' => 'ai-machine-learning',
                'description' => null,


                'price' => 149,
                'status' => 'active',
                'created_at' => '2026-03-04 07:44:14',
                'updated_at' => '2026-03-04 07:44:14',
            ],
        ];


        foreach ($i_c_t_courses as $course) {
            DB::table('i_c_t_courses')->insert([
                'id' => $course['id'],
                'instructor_id' => $course['instructor_id'],
                'schedule_id' => $course['schedule_id'],
                'thumbnail' => $course['thumbnail'],
                'title' => $course['title'],
                'slug' => $course['slug'],
                'description' => $course['description'],

                // ✅ ADDED ONLY THESE TWO
                'start_date' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                'duration' => rand(40, 100),

                'price' => $course['price'],
                'status' => $course['status'],
                'created_at' => $course['created_at'],
                'updated_at' => $course['updated_at'],
            ]);
        }



    }
}
