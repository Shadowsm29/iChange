<?php

use App\Circle;
use Illuminate\Database\Seeder;

class CirclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Circle::create([
            "name" => "Circle 1",
            "supercircle_id" => 1
        ]);

        Circle::create([
            "name" => "Circle 2",
            "supercircle_id" => 1
        ]);

        Circle::create([
            "name" => "Circle 3",
            "supercircle_id" => 1
        ]);

        Circle::create([
            "name" => "Circle 4",
            "supercircle_id" => 2
        ]);

        Circle::create([
            "name" => "Circle 5",
            "supercircle_id" => 2
        ]);

        Circle::create([
            "name" => "Circle 6",
            "supercircle_id" => 2
        ]);

        Circle::create([
            "name" => "Circle 7",
            "supercircle_id" => 3
        ]);

        Circle::create([
            "name" => "Circle 8",
            "supercircle_id" => 3
        ]);

        Circle::create([
            "name" => "Circle 9",
            "supercircle_id" => 3
        ]);
    }
}
