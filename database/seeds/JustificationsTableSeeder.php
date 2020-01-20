<?php

use App\Justification;
use Illuminate\Database\Seeder;

class JustificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Justification::Create([
            "name" => "Speed / Efficiency"
        ]);

        Justification::Create([
            "name" => "Quality improvement"
        ]);

        Justification::Create([
            "name" => "Cost Reduction / Avoidance"
        ]);
    }
}
