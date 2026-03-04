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
        $schedules = $schedules = [
            [
                'study_day' => 'Mon-Wed-Fri',
                'shift' => 'Morning',
                'start_time' => '08:30:00',
                'end_time' => '10:30:00',

            ],
            [
                'study_day' => 'Mon-Wed-Fri',
                'shift' => 'Morning',
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
            ],
            [
                'study_day' => 'Mon-Wed-Fri',
                'shift' => 'Afternoon',
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
            ],
            [
                'study_day' => 'Mon-Wed-Fri',
                'shift' => 'Evening',
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
            ],
            [
                'study_day' => 'Tue-Thu',
                'shift' => 'Morning',
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
            ],
            [
                'study_day' => 'Tue-Thu',
                'shift' => 'Afternoon',
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
            ],
            [
                'study_day' => 'Tue-Thu',
                'shift' => 'Evening',
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
            ],
            [
                'study_day' => 'Saturday',
                'shift' => 'Morning',
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
            ],
            [
                'study_day' => 'Saturday',
                'shift' => 'Afternoon',
                'start_time' => '13:00:00',
                'end_time' => '16:30:00',
            ],
            [
                'study_day' => 'Saturday',
                'shift' => 'Afternoon',
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
            ],
            [
                'study_day' => 'Sunday',
                'shift' => 'Morning',
                'start_time' => '08:30:00',
                'end_time' => '10:30:00',
            ],
            [
                'study_day' => 'Sunday',
                'shift' => 'Morning',
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
            ],
            [
                'study_day' => 'Sunday',
                'shift' => 'Afternoon',
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
            ],
            [
                'study_day' => 'Tue-Thu',
                'shift' => 'Morning',
                'start_time' => '08:30:00',
                'end_time' => '10:30:00',
            ],
            [
                'study_day' => 'Saturday',
                'shift' => 'Morning',
                'start_time' => '08:30:00',
                'end_time' => '10:30:00',
            ],
            [
                'study_day' => 'Mon-Wed-Fri',
                'shift' => 'Morning',
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
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
