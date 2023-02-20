<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            [
                'name' => 'Event one',
                'event_visibility' => 1,
                'event_max_participants' => 5,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Event two',
                'event_visibility' => 1,
                'event_max_participants' => 5,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
        ]);
    }
}
