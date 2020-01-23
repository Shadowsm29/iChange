<?php

use App\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        Status::create([
            "name" => "Initiated - Line Manager Approval"
        ]);

        //2
        Status::create([
            "name" => "Initiated - MT Approval"
        ]);

        //3
        Status::create([
            "name" => "Initiated - Central Resources Approval"
        ]);

        //4
        Status::create([
            "name" => "Initiated - Change Board Approval"
        ]);

        //5
        Status::create([
            "name" => "Approved - SME Assignment"
        ]);

        //6
        Status::create([
            "name" => "Work in Progress - Just Do It"
        ]);

        //7
        Status::create([
            "name" => "Work in Progress - RPA"
        ]);

        //8
        Status::create([
            "name" => "Work in Progress - COSMOS"
        ]);

        //9
        Status::create([
            "name" => "Work in Progress - LSA"
        ]);

        //10
        Status::create([
            "name" => "Correction Needed"
        ]);

        //11
        Status::create([
            "name" => "Cancelled"
        ]);

        //12
        Status::create([
            "name" => "Implemented"
        ]);

        //13
        Status::create([
            "name" => "Declined"
        ]);

         //14
         Status::create([
            "name" => "Finalized - MT Approval"
        ]);

        //15
        Status::create([
            "name" => "WIP - IT"
        ]);

        //16
        Status::create([
            "name" => "Work in Progress - Organizational"
        ]);

        //15
        Status::create([
            "name" => "Work in Progress - Business"
        ]);
    }
}
