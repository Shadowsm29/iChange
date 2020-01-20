<?php

use App\ChangeType;
use Illuminate\Database\Seeder;

class ChangeTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChangeType::create([
            "name" => "COSMOS"
        ]);

        ChangeType::create([
            "name" => "IT"
        ]);

        ChangeType::create([
            "name" => "Business"
        ]);

        ChangeType::create([
            "name" => "Organizational"
        ]);

        ChangeType::create([
            "name" => " Just Do It"
        ]);

        ChangeType::create([
            "name" => " RPA"
        ]);

        ChangeType::create([
            "name" => " LSS"
        ]);
    }
}
