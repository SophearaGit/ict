<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ICTScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            [
                'id' => 1,
                'study_day' => 'mon-wed-fri',
                'shift' => 'evening',
                'room' => null,
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => '2026-04-28 08:57:42',
                'updated_at' => '2026-04-28 08:57:42',
            ],
            [
                'id' => 2,
                'study_day' => 'tue-thu',
                'shift' => 'evening',
                'room' => null,
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => '2026-04-28 08:58:06',
                'updated_at' => '2026-04-28 08:58:06',
            ],
            [
                'id' => 3,
                'study_day' => 'saturday',
                'shift' => 'morning',
                'room' => null,
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
                'created_at' => '2026-04-28 08:58:53',
                'updated_at' => '2026-04-28 08:58:53',
            ],
            [
                'id' => 4,
                'study_day' => 'saturday',
                'shift' => 'afternoon',
                'room' => null,
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
                'created_at' => '2026-04-28 08:59:31',
                'updated_at' => '2026-04-28 11:51:33',
            ],
            [
                'id' => 5,
                'study_day' => 'mon-wed-fri',
                'shift' => 'morning',
                'room' => null,
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
                'created_at' => '2026-04-28 11:48:08',
                'updated_at' => '2026-04-28 11:48:08',
            ],
            [
                'id' => 6,
                'study_day' => 'tue-thu',
                'shift' => 'morning',
                'room' => null,
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
                'created_at' => '2026-04-28 11:49:34',
                'updated_at' => '2026-04-28 11:49:34',
            ],
            [
                'id' => 7,
                'study_day' => 'tue-thu',
                'shift' => 'afternoon',
                'room' => null,
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
                'created_at' => '2026-04-28 11:50:28',
                'updated_at' => '2026-04-28 11:50:28',
            ],
            [
                'id' => 8,
                'study_day' => 'sunday',
                'shift' => 'afternoon',
                'room' => null,
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
                'created_at' => '2026-04-28 11:52:13',
                'updated_at' => '2026-04-28 11:52:13',
            ],
            [
                'id' => 9,
                'study_day' => 'sunday',
                'shift' => 'morning',
                'room' => null,
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
                'created_at' => '2026-04-28 11:52:37',
                'updated_at' => '2026-04-28 11:52:37',
            ],
            [
                'id' => 10,
                'study_day' => 'mon-wed-fri',
                'shift' => 'afternoon',
                'room' => null,
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
                'created_at' => '2026-04-28 11:54:50',
                'updated_at' => '2026-04-28 11:54:50',
            ],
        ];


        foreach ($schedules as $schedule) {
            DB::table('i_c_t_schedules')->insert([
                'study_day' => $schedule['study_day'],
                'shift' => $schedule['shift'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
            ]);
        }


    }
}
