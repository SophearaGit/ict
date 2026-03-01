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
        $days = [
            'mon-wed-fri',
            'tue-thu',
            'saturday',
            'sunday',
        ];

        $shifts = [
            [
                'shift' => 'morning',
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
            ],
            [
                'shift' => 'afternoon',
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
            ],
            [
                'shift' => 'evening',
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
            ],
        ];

        $data = [];

        foreach ($days as $day) {
            foreach ($shifts as $shift) {
                $data[] = [
                    'study_day' => $day,
                    'shift' => $shift['shift'],
                    'start_time' => $shift['start_time'],
                    'end_time' => $shift['end_time'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('i_c_t_schedules')->insert($data);

    }
}
