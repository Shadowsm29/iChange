<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeeder::class);
         $this->call(ChangeTypesSeeder::class);
         $this->call(JustificationsTableSeeder::class);
         $this->call(CirclesTableSeeder::class);
         $this->call(SupercirclesTableSeed::class);
         $this->call(RolesTableSeeder::class);
         $this->call(StatusesTableSeeder::class);
         $this->call(RagStatusesSeeder::class);
    }
}
