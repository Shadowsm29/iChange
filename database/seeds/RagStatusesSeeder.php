<?php

use App\RagStatus;
use Illuminate\Database\Seeder;

class RagStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RagStatus::Create([
            "name" => "Red",
            "description" => "Project is not going well"
        ]);

        RagStatus::Create([
            "name" => "Amber",
            "description" => "Project is going well"
        ]);

        RagStatus::Create([
            "name" => "Green",
            "description" => "Project is going excellent"
        ]);
    }
}
