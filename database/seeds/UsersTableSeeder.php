<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole = Role::create([
            "name" => "user",
            "short_description" => "User",
            "description" => "Can initiate new Just Do It idea and track their progress."
        ]);

        $chgBoardRole = Role::create([
            "name" => "changeboard",
            "short_description" => "Change Board",
            "description" => "Can approve requests in change board step."
        ]);

        $mtRole = Role::create([
            "name" => "mt",
            "short_description" => "Management Team",
            "description" => "Can give approval for initial assesment and final approval for implementation. Sees all ideas."
        ]);

        $rpaRole = Role::create([
            "name" => "rpa",
            "short_description" => "RPA",
            "description" => "Can give initial assesment and work on RPA related ideas. Sees all ideas. Can manage content of the page."
        ]);

        $iamRole = Role::create([
            "name" => "iam",
            "short_description" => "IAM",
            "description" => "Can manage user access."
        ]);

        $superadminRole = Role::create([
            "name" => "superadmin",
            "short_description" => "Super Admin",
            "description" => "Full access."
        ]);

        $advancedUserRole = Role::create([
            "name" => "advanceduser",
            "short_description" => "Advanced User",
            "description" => "User with advanced permissions - can raise all types of requests."
        ]);

        $cosmosRole = Role::create([
            "name" => "cosmos",
            "short_description" => "COSMOS",
            "description" => "Team who works on COSMOS projects"
        ]);

        $lssRole = Role::create([
            "name" => "lss",
            "short_description" => "LSS",
            "description" => "Team who works on LSS projects"
        ]);

        $itRole = Role::create([
            "name" => "it",
            "short_description" => "IT",
            "description" => "Team who works on IT projects"
        ]);

        $superadminUser = User::create([
            "name" => "superadmin",
            "email" => "superadmin@test.com",
            "password" => Hash::make("password"),
        ]);

        $superadminUser->update(["manager_id" => $superadminUser["id"]]);
        
        $rpaUser = User::create([
            "name" => "RPA User",
            "email" => "rpa@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $superadminUser["id"]
        ]);

        $managerUser = User::create([
            "name" => "User User (Line Manager)",
            "email" => "manager@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $superadminUser["id"]
        ]);

        $userUser = User::create([
            "name" => "User User",
            "email" => "user@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $managerUser["id"]
        ]);

        $advancedUserUser = User::create([
            "name" => "Advanced User",
            "email" => "advanced.user@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $managerUser["id"]
        ]);

        $chgBoardUSer = User::create([
            "name" => "Change Board User",
            "email" => "change.board@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $superadminUser["id"]
        ]);

        $mtUser = User::create([
            "name" => "MT User",
            "email" => "mt@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $superadminUser["id"]
        ]);

        $iamUser = User::create([
            "name" => "IAM User",
            "email" => "iam@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $superadminUser["id"]
        ]);

        $cosmosUser = User::create([
            "name" => "COSMOS User",
            "email" => "cosmos@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $superadminUser["id"]
        ]);

        $lssUser = User::create([
            "name" => "LSS User",
            "email" => "lss@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $superadminUser["id"]
        ]);

        $itUser = User::create([
            "name" => "IT User",
            "email" => "it@test.com",
            "password" => Hash::make("password"),
            "manager_id" => $superadminUser["id"]
        ]);

        $rpaUser->roles()->attach($rpaRole);
        $rpaUser->roles()->attach($userRole);
        $userUser->roles()->attach($userRole);
        $managerUser->roles()->attach($userRole);
        $advancedUserUser->roles()->attach($advancedUserRole);
        $chgBoardUSer->roles()->attach($chgBoardRole);
        $mtUser->roles()->attach($mtRole);
        $iamUser->roles()->attach($iamRole);
        $cosmosUser->roles()->attach($cosmosRole);
        $lssUser->roles()->attach($lssRole);
        $itUser->roles()->attach($itRole);

        $superadminUser->roles()->attach($userRole);
        $superadminUser->roles()->attach($advancedUserRole);
        $superadminUser->roles()->attach($mtRole);
        $superadminUser->roles()->attach($cosmosRole);
        $superadminUser->roles()->attach($lssRole);
        $superadminUser->roles()->attach($itRole);
        $superadminUser->roles()->attach($rpaRole);
        $superadminUser->roles()->attach($iamRole);
    }
}
