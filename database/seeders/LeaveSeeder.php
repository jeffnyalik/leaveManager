<?php

namespace Database\Seeders;

use App\Models\leaves\Leave;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Leave::truncate();
        $faker = \Faker\Factory::create();
        for($i = 0; $i < 10; $i ++){
            Leave::create([
                'leave_name' => $faker->word,
                'leave_type' => $faker->word,
                'leave_status' => $faker->boolean(),
            ]);
        }
        //code here
    }
}
