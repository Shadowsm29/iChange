<?php

use App\Supercircle;
use Illuminate\Database\Seeder;

class SupercirclesTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supercircle::create([
            "name" => "Supercircle 1"
        ]);

        Supercircle::create([
            "name" => "Supercircle 2"
        ]);

        Supercircle::create([
            "name" => "Supercircle 3"
        ]);
    }
}
